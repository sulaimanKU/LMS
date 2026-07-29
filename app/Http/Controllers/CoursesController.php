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
        $filter           = $request->get('filter', 'all');
        $search           = trim($request->get('search', ''));
        $category         = $request->get('category');
        $selectedCourseId = $request->get('course_id');

        $allCoursesList = Courses::where('category', '!=', 'Workshop')
            ->withCount('enrollments')
            ->orderBy('title')
            ->get();

        // 1. Check if search query matches any student email, phone (mobile), or name
        $matchingUserIds = [];
        $matchingCourseIdsFromUsers = [];

        if (!empty($search)) {
            // Find users by name, email, or phone
            $matchedUsers = \App\Models\User::where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->get();

            // Find registrations by name, email, or phone
            $matchedRegs = \App\Models\Registration::where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%")
                ->pluck('email');

            $allMatchedEmails = $matchedUsers->pluck('email')->merge($matchedRegs)->unique()->filter();

            if ($allMatchedEmails->isNotEmpty()) {
                $usersFromSearch = \App\Models\User::whereIn('email', $allMatchedEmails)
                    ->with('enrolledModules')
                    ->get();

                $matchingUserIds = $usersFromSearch->pluck('id')->toArray();
                foreach ($usersFromSearch as $u) {
                    foreach ($u->enrolledModules as $m) {
                        $matchingCourseIdsFromUsers[] = $m->id;
                    }
                }
            }
        }

        // Base query for regular courses
        $baseQuery = Courses::where('category', '!=', 'Workshop');

        if ($category) {
            $baseQuery->where('category', $category);
        }

        if (!empty($search)) {
            $baseQuery->where(function($q) use ($search, $matchingCourseIdsFromUsers) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%")
                  ->orWhere('details', 'LIKE', "%{$search}%");

                if (!empty($matchingCourseIdsFromUsers)) {
                    $q->orWhereIn('id', array_unique($matchingCourseIdsFromUsers));
                }
            });
        }

        // Stats
        $totalCourses    = (clone $baseQuery)->count();
        $activeCourses   = (clone $baseQuery)->where('status', 'active')->count();
        $inactiveCourses = (clone $baseQuery)->where('status', 'inactive')->count();

        $query = (clone $baseQuery)->with(['teacher'])->withCount(['lessons', 'enrollments']);
        if ($filter === 'active') {
            $query->where('status', 'active');
        } elseif ($filter === 'inactive') {
            $query->where('status', 'inactive');
        }

        $courses  = $query->orderBy('title')->paginate(12)->withQueryString();
        $teachers = Teacher::orderBy('name')->get();
        $categories = Courses::where('category', '!=', 'Workshop')->distinct()->pluck('category');

        $totalEnrolled   = \DB::table('enrollments')
            ->whereIn('module_id', Courses::where('category', '!=', 'Workshop')->pluck('id'))
            ->whereIn('status', ['active', 'completed'])
            ->count();

        // Enrolled Students list logic
        $selectedCourse = null;
        $enrolledStudents = collect();

        if ($selectedCourseId) {
            $selectedCourse = Courses::with(['teacher'])->withCount(['lessons', 'enrollments'])->find($selectedCourseId);
        }

        // Load student list if a course is selected OR if searching for student by email/phone/name
        if ($selectedCourse || !empty($matchingUserIds)) {
            $userQuery = \App\Models\User::query();

            if ($selectedCourse) {
                $userQuery->whereHas('enrolledModules', function($q) use ($selectedCourseId) {
                    $q->where('modules.id', $selectedCourseId);
                });
            } else {
                $userQuery->whereIn('id', $matchingUserIds);
            }

            $users = $userQuery->with([
                'enrolledModules',
                'certificates',
                'submissions'
            ])->get();

            $emails = $users->pluck('email')->unique()->filter();
            $registrations = \App\Models\Registration::whereIn('email', $emails)->with('slips')->get()->keyBy('email');

            $enrolledStudents = $users->map(function($user) use ($selectedCourseId, $registrations) {
                $reg = $registrations->get($user->email);
                $pivot = $selectedCourseId ? $user->enrolledModules->firstWhere('id', $selectedCourseId)?->pivot : $user->enrolledModules->first()?->pivot;
                $cert = $selectedCourseId ? $user->certificates->firstWhere('module_id', $selectedCourseId) : $user->certificates->first();
                $attendanceCount = \App\Models\Attendance::where('user_id', $user->id)->count();

                return (object)[
                    'id'               => $user->id,
                    'registration_id'  => $reg?->id ?? $user->id,
                    'name'             => $user->name,
                    'email'            => $user->email,
                    'phone'            => $user->phone ?? $reg?->phone ?? 'N/A',
                    'profile_image'    => $user->profile_image,
                    'institution'      => $reg?->institution ?? 'N/A',
                    'research_area'    => $reg?->research_area ?? 'N/A',
                    'enrollment_status' => $pivot?->status ?? 'active',
                    'enrolled_at'      => $pivot?->created_at ? $pivot->created_at->format('M d, Y') : 'N/A',
                    'certificate'      => $cert,
                    'attendance_count' => $attendanceCount,
                    'submissions_count'=> $user->submissions->count(),
                    'registration'     => $reg,
                ];
            });
        }

        return view('layouts.courses.index', compact(
            'courses', 'teachers', 'filter', 'search', 'category', 'categories',
            'totalCourses', 'activeCourses', 'inactiveCourses', 'totalEnrolled',
            'allCoursesList', 'selectedCourseId', 'selectedCourse', 'enrolledStudents'
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
            'workshop_number'   => 'nullable|integer',
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
            'workshop_number'   => $request->workshop_number,
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
            'workshop_number'   => 'nullable|integer',
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
            'workshop_number'   => $request->workshop_number,
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
