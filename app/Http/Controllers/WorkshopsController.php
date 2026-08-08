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
        $workshopNumFilter = $request->get('workshop_number');

        // Base query for workshops (matches category = 'Workshop' OR modules that have a workshop_number)
        $baseQuery = Courses::where(function($q) {
            $q->where('category', 'Workshop')
              ->orWhereNotNull('workshop_number');
        });

        if ($workshopNumFilter) {
            $baseQuery->where('workshop_number', $workshopNumFilter);
        }

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

        // Distinct workshop numbers for quick filter/bulk action
        $availableWorkshopNumbers = Courses::whereNotNull('workshop_number')
            ->distinct()
            ->orderBy('workshop_number')
            ->pluck('workshop_number');

        // Global participants for all workshops
        $totalEnrolled = \DB::table('enrollments')
            ->whereIn('module_id', (clone $baseQuery)->pluck('id'))
            ->whereIn('status', ['active', 'completed'])
            ->count();

        return view('layouts.workshops.index', compact(
            'workshops', 'teachers', 'filter', 'search', 'workshopNumFilter',
            'totalWorkshops', 'activeWorkshops', 'inactiveWorkshops', 'totalEnrolled',
            'availableWorkshopNumbers'
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

        try {
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create workshop: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $workshop = Courses::findOrFail($id);
        return view('layouts.workshops.edit', compact('workshop'));
    }

    public function update(Request $request, $id)
    {
        $workshop = Courses::findOrFail($id);

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

        try {
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update workshop: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $workshop = Courses::findOrFail($id);

        if ($workshop->enrollments()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete "' . $workshop->title . '" — it has enrolled participants.');
        }

        try {
            $workshop->teacher()->detach();
            foreach ($workshop->lessons as $lesson) {
                $lesson->resources()->delete();
            }
            $workshop->lessons()->delete();
            $workshop->onlineclasses()->delete();
            if ($workshop->image) {
                \Storage::disk('public')->delete($workshop->image);
            }
            $workshop->delete();

            return redirect()->route('workshops.index')->with('success', 'Workshop deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete workshop: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        try {
            $workshop = Courses::findOrFail($id);
            $newStatus = $workshop->status === 'active' ? 'inactive' : 'active';
            $workshop->update(['status' => $newStatus]);

            return redirect()->back()->with('success', '"' . $workshop->title . '" status changed to ' . ucfirst($newStatus) . '.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to toggle status: ' . $e->getMessage());
        }
    }

    public function bulkStatus(Request $request)
    {
        $status = $request->input('status', 'inactive');
        $workshopNumber = $request->input('workshop_number');

        try {
            $query = Courses::where(function($q) {
                $q->where('category', 'Workshop')
                  ->orWhereNotNull('workshop_number');
            });

            if ($workshopNumber !== null && $workshopNumber !== '' && $workshopNumber !== 'all') {
                $query->where('workshop_number', $workshopNumber);
                $label = 'Workshop #' . $workshopNumber;
            } else {
                $label = 'All Workshops';
            }

            $count = $query->update(['status' => $status]);

            return redirect()->back()->with('success', "Updated {$count} item(s) in {$label} to " . ucfirst($status) . '.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Bulk update failed: ' . $e->getMessage());
        }
    }
}
