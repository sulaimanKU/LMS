<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\OnlineClass;
use App\Models\Submission;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController
{
    public function studentIndex()
    {
        OnlineClass::autoExpireLive();

        $user = auth()->user();

        $enrollments = Enrollment::with(['modules.teacher'])
            ->where('user_id', $user->id)
            ->get();

        $courseCount = $enrollments->count();

        $enrolledModuleIds = $enrollments->pluck('module_id');

        $attendanceCount = \App\Models\Attendance::where('user_id', $user->id)->count();

        $submittedIds = Submission::where('user_id', $user->id)->pluck('assignment_id');

        $pendingCount = Assignment::whereHas('onlineClass', fn($q) => $q->whereIn('module_id', $enrolledModuleIds))
            ->whereNotIn('id', $submittedIds)
            ->where('due_date', '>=', now())
            ->count();

        $overdueCount = Assignment::whereHas('onlineClass', fn($q) => $q->whereIn('module_id', $enrolledModuleIds))
            ->whereNotIn('id', $submittedIds)
            ->where('due_date', '<', now())
            ->count();

        $submissionsCount = Submission::where('user_id', $user->id)->count();

        $liveClass = OnlineClass::with(['module', 'teacher'])
            ->whereIn('module_id', $enrolledModuleIds)
            ->where('status', 'live')
            ->first();

        $upcomingClasses = OnlineClass::with(['module', 'teacher'])
            ->whereIn('module_id', $enrolledModuleIds)
            ->where('status', 'upcoming')
            ->orderBy('class_date')->orderBy('start_time')
            ->take(4)->get();

        $recentSubmissions = Submission::with(['assignment'])
            ->where('user_id', $user->id)
            ->orderBy('submitted_at', 'desc')
            ->take(4)->get();

        return view('dashboard.studdent', compact(
            'user', 'enrollments', 'courseCount',
            'attendanceCount', 'pendingCount', 'overdueCount', 'submissionsCount',
            'liveClass', 'upcomingClasses', 'recentSubmissions'
        ));
    }
    public function enrolledCourses()
    {
        OnlineClass::autoExpireLive();

        $userId = Auth::id();

        $courseEnrolled = Enrollment::with(['modules.teacher', 'modules.lessons'])
            ->where('user_id', $userId)
            ->get();

        // Live class IDs for the student's modules
        $enrolledModuleIds = $courseEnrolled->pluck('module_id');
        $liveModuleIds = OnlineClass::where('status', 'live')
            ->whereIn('module_id', $enrolledModuleIds)
            ->pluck('module_id')
            ->flip(); // use as a lookup set

        return view('studentLayouts.enrolledCourses', compact('courseEnrolled', 'liveModuleIds'));
    }
    public function jionClass()
    {
        OnlineClass::autoExpireLive();

        $userId = auth()->id();

        $enrolledModuleIds = Enrollment::where('user_id', $userId)->pluck('module_id');

        $liveClass = OnlineClass::with(['module', 'teacher'])
            ->whereIn('module_id', $enrolledModuleIds)
            ->where('status', 'live')
            ->first();

        $upcomingClasses = OnlineClass::with(['module', 'teacher'])
            ->whereIn('module_id', $enrolledModuleIds)
            ->where('status', 'upcoming')
            ->orderBy('class_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();

        $finishedClasses = OnlineClass::with(['module', 'teacher'])
            ->whereIn('module_id', $enrolledModuleIds)
            ->where('status', 'finished')
            ->orderBy('class_date', 'desc')
            ->take(10)->get();

        $cancelledClasses = OnlineClass::with(['module', 'teacher'])
            ->whereIn('module_id', $enrolledModuleIds)
            ->where('status', 'cancelled')
            ->orderBy('class_date', 'desc')
            ->take(6)->get();

        $upcomingCount  = $upcomingClasses->count();
        $finishedCount  = $finishedClasses->count();

        return view('studentLayouts.jionClass', compact(
            'liveClass', 'upcomingClasses', 'finishedClasses',
            'cancelledClasses', 'upcomingCount', 'finishedCount'
        ));
    }


    public function learningMaterialsView()
    {
        $user = auth()->user();


        $courses = $user->enrolledModules()
            ->with(['teacher', 'lessons.resources'])
            ->get();
        return view('studentLayouts.learningMaterialsView', compact('courses'));
    }
    public function assigmentsUploadView()
    {

        $user = auth()->user();

        $enrolledModuleIds = Enrollment::where('user_id', $user->id)
            ->pluck('module_id');



        $userId = $user->id;
        $assignments = Assignment::with([
            'onlineClass',
            'submissions' => fn($q) => $q->where('user_id', $userId),
        ])->whereHas('onlineClass', function ($query) use ($enrolledModuleIds) {
            $query->whereIn('module_id', $enrolledModuleIds);
        })->orderBy('due_date', 'asc')->get();

        return view('studentLayouts.assigmentsUploadView', compact('assignments'));
    }

    public function storeSubmission(Request $request)
    {
        $request->validate([
            'assignment_id'   => 'required|exists:assignments,id',
            'submission_file' => 'required|file|mimes:pdf,docx,doc,zip|max:10240',
        ]);

        $userId     = auth()->id();
        $assignment = Assignment::with('onlineClass')->findOrFail($request->assignment_id);

        // Ensure the assignment belongs to a module the student is enrolled in
        $enrolledModuleIds = Enrollment::where('user_id', $userId)->pluck('module_id');
        if (!$assignment->onlineClass || !$enrolledModuleIds->contains($assignment->onlineClass->module_id)) {
            return back()->withErrors(['assignment_id' => 'You are not enrolled in the module for this assignment.']);
        }

        // Prevent duplicate submissions
        if (Submission::where('assignment_id', $assignment->id)->where('user_id', $userId)->exists()) {
            return back()->withErrors(['assignment_id' => 'You have already submitted this assignment.']);
        }

        // Determine status: late if past due date
        $now    = now();
        $status = $now->gt($assignment->due_date) ? 'late' : 'pending';

        $path = $request->file('submission_file')->store('submissions', 'public');

        Submission::create([
            'assignment_id' => $assignment->id,
            'user_id'       => $userId,
            'file_path'     => $path,
            'student_note'  => $request->student_note,
            'status'        => $status,
            'submitted_at'  => $now,
        ]);

        return back()->with('success', 'Your work has been submitted successfully!');
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'content' => 'required|string|max:1000',
        ]);

        $user = auth()->user();

        Review::create([
            'user_id' => $user->id,
            'name'    => $user->name,
            'content' => $request->content,
            'rating'  => $request->rating,
            'role'    => 'Student',
            'status'  => 'active',
        ]);

        return back()->with('success', 'Thank you for your feedback! Your testimonial is now live.');
    }
}
