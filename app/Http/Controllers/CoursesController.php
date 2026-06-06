<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CoursesController
{
    public function courses_index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Courses::with(['teacher'])
            ->withCount(['lessons', 'enrollments']);

        if ($filter === 'active') {
            $query->where('status', 'active');
        } elseif ($filter === 'inactive') {
            $query->where('status', 'inactive');
        }

        $courses  = $query->orderBy('title')->paginate(12)->withQueryString();
        $teachers = Teacher::orderBy('name')->get();

        $totalCourses   = Courses::count();
        $activeCourses  = Courses::where('status', 'active')->count();
        $inactiveCourses = Courses::where('status', 'inactive')->count();
        $totalEnrolled  = \DB::table('enrollments')->whereIn('status', ['active', 'completed'])->count();

        return view('layouts.courses.index', compact(
            'courses', 'teachers', 'filter',
            'totalCourses', 'activeCourses', 'inactiveCourses', 'totalEnrolled'
        ));
    }

    public function create()
    {
        return view('layouts.courses.create');
    }

    public function edit($id)
    {
        $course = Courses::findOrFail($id);
        return view('layouts.courses.edit', compact('course'));
    }

    public function show($id)
    {
        $course = Courses::with(['teacher', 'lessons'])
            ->withCount(['lessons', 'enrollments'])
            ->findOrFail($id);
            
        return view('home_layouts.courseDetail', compact('course'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'             => 'required|string|max:255|unique:modules,title',
            'category'          => 'required|string|max:100',
            'price'             => 'required|numeric|min:0',
            'duration'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'details'           => 'nullable|string',
            'status'            => 'required|in:active,inactive',
            'image'             => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('courses', 'public');
        }

        Courses::create([
            'title'             => $request->title,
            'slug'              => Str::slug($request->title) . '-' . time(),
            'category'          => $request->category,
            'price'             => $request->price,
            'duration'          => $request->duration,
            'short_description' => $request->short_description,
            'details'           => $request->details,
            'status'            => $request->status,
            'image'             => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Course "' . $request->title . '" created successfully.');
    }

    public function update(Request $request, $id)
    {
        $course = Courses::findOrFail($id);

        $request->validate([
            'title'             => 'required|string|max:255|unique:modules,title,' . $id,
            'category'          => 'required|string|max:100',
            'price'             => 'required|numeric|min:0',
            'duration'          => 'nullable|string|max:100',
            'short_description' => 'nullable|string|max:500',
            'details'           => 'nullable|string',
            'status'            => 'required|in:active,inactive',
            'image'             => 'nullable|image|max:2048',
        ]);

        $data = [
            'title'             => $request->title,
            'slug'              => Str::slug($request->title),
            'category'          => $request->category,
            'price'             => $request->price,
            'duration'          => $request->duration,
            'short_description' => $request->short_description,
            'details'           => $request->details,
            'status'            => $request->status,
        ];

        if ($request->hasFile('image')) {
            if ($course->image) {
                \Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()->back()->with('success', '"' . $course->title . '" updated successfully.');
    }

    public function destroy($id)
    {
        $course = Courses::findOrFail($id);

        if ($course->enrollments()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete "' . $course->title . '" — it has enrolled students.');
        }

        $course->teacher()->detach();
        // Delete lesson resources before deleting lessons
        foreach ($course->lessons as $lesson) {
            $lesson->resources()->delete();
        }
        $course->lessons()->delete();
        $course->onlineclasses()->delete();
        $course->delete();

        return redirect()->back()->with('success', 'Course deleted successfully.');
    }
}
