<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WorkshopsController
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('search');

        // Base query for workshops
        $baseQuery = Courses::where('category', 'Workshop');

        // Apply search if present
        if ($search) {
            $baseQuery->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhere('details', 'LIKE', "%{$search}%")
                  ->orWhere('workshop_number', $search)
                  ->orWhere('workshop_number', 'LIKE', "%{$search}%");
            });
        }

        // Clone for stats before status filter
        $totalWorkshops    = (clone $baseQuery)->count();
        $activeWorkshops   = (clone $baseQuery)->where('status', 'active')->count();
        $inactiveWorkshops = (clone $baseQuery)->where('status', 'inactive')->count();

        // Main query with pagination and eager loading
        $query = (clone $baseQuery)->with(['teacher'])->withCount(['lessons', 'enrollments']);
        if ($filter === 'active') {
            $query->where('status', 'active');
        } elseif ($filter === 'inactive') {
            $query->where('status', 'inactive');
        }

        $workshops = $query->orderBy('workshop_number', 'desc')->paginate(12)->withQueryString();
        $teachers  = Teacher::orderBy('name')->get();

        // Global participants for all workshops
        $totalEnrolled     = \DB::table('enrollments')
            ->whereIn('module_id', Courses::where('category', 'Workshop')->pluck('id'))
            ->whereIn('status', ['active', 'completed'])
            ->count();

        return view('layouts.workshops.index', compact(
            'workshops', 'teachers', 'filter', 'search',
            'totalWorkshops', 'activeWorkshops', 'inactiveWorkshops', 'totalEnrolled'
        ));
    }

    public function create()
    {
        return view('layouts.workshops.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255|unique:modules,title',
            'workshop_number'   => 'nullable|integer',
            'price'             => 'required|numeric|min:0',
            'duration'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'details'           => 'nullable|string',
            'status'            => 'required|in:active,inactive',
            'image'             => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('workshops', 'public');
        }

        Courses::create([
            'title'             => $request->title,
            'workshop_number'   => $request->workshop_number,
            'slug'              => Str::slug($request->title) . '-' . time(),
            'category'          => 'Workshop',
            'price'             => $request->price,
            'duration'          => $request->duration,
            'short_description' => $request->short_description,
            'details'           => $request->details,
            'status'            => $request->status,
            'image'             => $imagePath,
        ]);

        return redirect()->route('workshops.index')->with('success', 'Workshop "' . $request->title . '" created successfully.');
    }

    public function edit($id)
    {
        $workshop = Courses::where('category', 'Workshop')->findOrFail($id);
        return view('layouts.workshops.edit', compact('workshop'));
    }

    public function update(Request $request, $id)
    {
        $workshop = Courses::where('category', 'Workshop')->findOrFail($id);

        $request->validate([
            'title'             => 'required|string|max:255|unique:modules,title,' . $id,
            'workshop_number'   => 'nullable|integer',
            'price'             => 'required|numeric|min:0',
            'duration'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'details'           => 'nullable|string',
            'status'            => 'required|in:active,inactive',
            'image'             => 'nullable|image|max:2048',
        ]);

        $data = [
            'title'             => $request->title,
            'workshop_number'   => $request->workshop_number,
            'slug'              => Str::slug($request->title),
            'price'             => $request->price,
            'duration'          => $request->duration,
            'short_description' => $request->short_description,
            'details'           => $request->details,
            'status'            => $request->status,
        ];

        if ($request->hasFile('image')) {
            if ($workshop->image) {
                \Storage::disk('public')->delete($workshop->image);
            }
            $data['image'] = $request->file('image')->store('workshops', 'public');
        }

        $workshop->update($data);

        return redirect()->route('workshops.index')->with('success', 'Workshop "' . $workshop->title . '" updated successfully.');
    }

    public function destroy($id)
    {
        $workshop = Courses::where('category', 'Workshop')->findOrFail($id);

        if ($workshop->enrollments()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete "' . $workshop->title . '" — it has enrolled participants.');
        }

        $workshop->teacher()->detach();
        foreach ($workshop->lessons as $lesson) {
            $lesson->resources()->delete();
        }
        $workshop->lessons()->delete();
        $workshop->onlineclasses()->delete();
        $workshop->delete();

        return redirect()->route('workshops.index')->with('success', 'Workshop deleted successfully.');
    }
}
