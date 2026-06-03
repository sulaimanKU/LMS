<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Courses;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonResource;
use App\Models\OnlineClass;
use App\Models\Submission;
use App\Models\Teacher;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class TeacherController
{

 public function teacherMain()
{
    OnlineClass::autoExpireLive();

    $user    = auth()->user();
    $teacher = Teacher::where('user_id', $user->id)->first();

    $coursesCount     = 0;
    $studentsCount    = 0;
    $liveClasses      = collect();
    $upcomingClasses  = collect();
    $recentSubs       = collect();
    $pendingGrading   = 0;
    $totalAssignments = 0;
    $assignedModules  = collect();

    if ($teacher) {
        // $teacher->courses() already points to the modules table
        $courses = $teacher->courses()->get();

        $coursesCount = $courses->count();

        $moduleIds = $courses->pluck('id');

        $studentsCount = \App\Models\Enrollment::whereIn('module_id', $moduleIds)
            ->distinct('user_id')->count('user_id');

        // Each "course" record IS a module — attach extra display fields
        $assignedModules = $courses->map(function ($module) {
            $module->course_title   = $module->title; // same record, no parent
            $module->students_count = \App\Models\Enrollment::where('module_id', $module->id)
                                        ->distinct('user_id')->count('user_id');
            return $module;
        });

        $liveClasses = OnlineClass::with('module')
            ->where('teacher_id', $teacher->id)
            ->where('status', 'live')
            ->get();

        $upcomingClasses = OnlineClass::with('module')
            ->where('teacher_id', $teacher->id)
            ->where('status', 'upcoming')
            ->orderBy('class_date')->orderBy('start_time')
            ->take(5)->get();

        $totalAssignments = Assignment::where('teacher_id', $user->id)->count();

        $pendingGrading = Submission::whereHas('assignment', fn($q) => $q->where('teacher_id', $user->id))
            ->where('status', 'pending')->count();

        $recentSubs = Submission::with(['user', 'assignment'])
            ->whereHas('assignment', fn($q) => $q->where('teacher_id', $user->id))
            ->orderBy('submitted_at', 'desc')
            ->take(5)->get();
    }

    return view('dashboard.teacher', compact(
        'user', 'teacher', 'coursesCount', 'studentsCount',
        'liveClasses', 'upcomingClasses', 'recentSubs',
        'pendingGrading', 'totalAssignments',
        'assignedModules'
    ));
}

    public function teacherClassView()
    {
        OnlineClass::autoExpireLive();

        $teacher = auth()->user()->teacher;
        //    dd($teacher);
        if (!$teacher) {
            return redirect()->back()->with('error', 'only teacher can access this page');
        }

        $teacher_courses = $teacher->courses;
        $scheduled_classes = $teacher->onlineClasses()
            ->with('module')
            ->orderBy('id', 'desc')
            ->get();


        return view('teacherLayouts.teacherClassView', compact('teacher_courses', 'scheduled_classes'));
    }

    public function teacherOnline_classesStore(Request $request)
    {
        $validated = $request->validate([
            'module_id'        => 'required|exists:modules,id',
            'title'            => 'required|string|max:255',
            'class_date'       => 'required|date',
            'start_time'       => 'required',
            'meeting_link'     => 'required|url',
            'duration'         => 'nullable|integer',
            'meeting_id'       => 'nullable|string',
            'meeting_password' => 'nullable|string',
            'description'      => 'nullable|string|max:500',
        ]);

        $teacher_id = auth()->user()->teacher->id;

        OnlineClass::create([

            'module_id' => $validated['module_id'],
            'teacher_id' => $teacher_id,
            'title' => $validated['title'],
            'class_date' => $validated['class_date'],
            'start_time' => $validated['start_time'],
            'meeting_link' => $validated['meeting_link'],
            'duration' => $validated['duration'],
            'meeting_id' => $validated['meeting_id'],
            'meeting_password' => $validated['meeting_password'],
            'description' => $validated['description']

        ]);

        return redirect()->back()->with('success', 'Class created Successfully');
    }

    public function teacherOnline_classesUpdateStatus(Request $request, $id)
    {
        $user = auth()->user()->id;
        $teacherRecord = Teacher::where('user_id', $user)->first();

        if (!($teacherRecord)) {
            return redirect()->back()->with('error', 'Teacher profile not found.');
        }
        $teacherId = $teacherRecord->id;

        $onlineClass = OnlineClass::where('id', $id)->where('teacher_id', $teacherId)->firstorFail();




        $newStatus = match (true) {
            $request->has('start')  => 'live',
            $request->has('end')    => 'finished',
            $request->has('cancel') => 'cancelled',
            default => 'upcoming'
        };

        if (!$newStatus) {
            return redirect()->back()->with('error', 'Invalid action requested.');
        }
        $onlineClass->update(['status' => $newStatus]);

        if ($newStatus === 'live') {
            event(new \App\Events\ClassStarted($onlineClass));
        }
        $messages = [
            'live'      => 'Success! The class is now live for students.',
            'finished'  => 'The session has been successfully closed.',
            'cancelled' => 'The class has been removed from the schedule.'
        ];
        return redirect()->back()->with('success', $messages[$newStatus]);
    }
    public function teacherLessonsDestroy($id)
    {
        $del = Lesson::findorFail($id);
        $del->delete();
        return redirect()->back()->with('success', 'Lesson deleted successfully');
    }
    public function teacherLessonsUpdate(Request $request, $id)
    {
        $lesson = Lesson::findorFail($id);
        $validated = $request->validate([
            'module_id'    => 'required|exists:modules,id',
            'title'        => 'required|string|max:255',
            'order_number' => 'required|integer',
            'short_text'   => 'nullable|string',
            'documnet_path'    => 'nullable|url',
            'full_content' => 'nullable|string',
        ]);
        if ($lesson->title !== $request->title) {
            $validated['slug'] = Str::slug($request->title) . '-' . rand(100, 999);
        }
        $lesson->update([
            'module_id' => $request->module_id,
            'title' => $request->title,
            'slug' => $validated['slug'],
            'short_text' => $request->short_text,
            'full_content' => $request->full_content,
            'documnet_path' => $request->documnet_path,
            'order_number' => $request->order_number,

        ]);
        return redirect()->back()->with('success', 'Lesson updated successfully!');
    }

    public function manageLessonsView()
{
    $teacher = auth()->user()->teacher;

    if (!$teacher) {
        dd('No teacher found');
    }

    $myModules = $teacher->courses;


    $lessons = Lesson::with('module')
        ->where('teacher_id', $teacher->id)
        ->get();


    if ($myModules->isNotEmpty()) {
        return view('teacherLayouts.manageLessonsView', compact('myModules', 'lessons'));
    }

    return redirect()->back()->with('error', 'Something went wrong.');
}

    public function teacherLessonsStore(Request $request)
    {
        $validated = $request->validate([
            'module_id'    => 'required|exists:modules,id',
            'title'        => 'required|string|max:255',
            'order_number' => 'required|integer',
            'short_text'   => 'nullable|string|max:500',
            'full_content' => 'nullable|string',
            'documnet_path'    => 'nullable|url',
        ]);
        $validated['slug'] = Str::slug($request->title) . '-' . rand(100, 999);
        $validated['teacher_id'] = auth()->user()->teacher->id;
        Lesson::create($validated);
        return redirect()->back()->with('success', 'New lesson added successfully to the curriculum!');
    }


    public function createOnlineClass()
    {
        OnlineClass::autoExpireLive();

        $user    = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return back()->with('error', 'Teacher profile not found.');
        }

        $liveClasses = OnlineClass::with('module')
            ->where('teacher_id', $teacher->id)
            ->where('status', 'live')
            ->get();

        $upcomingClasses = OnlineClass::with('module')
            ->where('teacher_id', $teacher->id)
            ->where('status', 'upcoming')
            ->orderBy('class_date')->orderBy('start_time')
            ->get();

        $teacher_courses = $teacher->courses()->get();

        return view('teacherLayouts.createOnlineClass', compact(
            'liveClasses', 'upcomingClasses', 'teacher_courses'
        ));
    }

    public function recordedLeacture()
    {

        return view('teacherLayouts.recordedLeacture');
    }
    public function stdGradeUpload()
    {

        return view('teacherLayouts.stdGradeUpload');
    }
    public function assignmentReviews()
    {
        $user    = auth()->user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if (!$teacher) {
            return back()->with('error', 'Teacher profile not found.');
        }

        $myAssignments = Assignment::where('teacher_id', $teacher->user_id)
            ->with('onlineClass')
            ->withCount('submissions')
            ->orderBy('created_at', 'desc')
            ->get();

        $studentSubmissions = Submission::whereHas('assignment', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->user_id);
            })
            ->with(['user', 'assignment'])
            ->orderBy('submitted_at', 'desc')
            ->get();

        $classes = OnlineClass::where('teacher_id', $teacher->id)->get();

        return view('teacherLayouts.assignmentReviews', compact('myAssignments', 'studentSubmissions', 'classes'));
    }


    public function submitGrade(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|exists:submissions,id',
            'grade'         => 'required|integer|min:0|max:100',
            'teacher_comment' => 'nullable|string|max:1000',
        ]);

        $submission = Submission::findOrFail($request->submission_id);


        $submission->update([
            'grade'           => $request->grade,
            'teacher_comment' => $request->teacher_comment,
            'status'          => 'graded',
        ]);

        return back()->with('success', 'Student work has been graded!');
    }
    public function uploadMaterialsIndex()
    {
        $teacher = auth()->user()->teacher;
        $lessons = Lesson::where('teacher_id', $teacher->id)
            ->with('module')
            ->get();
        $resources = LessonResource::with(['lesson.module'])
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->get();
        return view('teacherLayouts.uploadMaterialsIndex', compact('lessons', 'resources'));
    }


    public function teacherResourcesStore(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required',
            'file' => 'required|file|mimes:pdf,doc,docx,zip,ppt,pptx'
        ]);

        try {

            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('lesson_materials', 'public');

                LessonResource::create([

                    'lesson_id' => $request->lesson_id,
                    'title' => $request->title,
                    'file_path' => $path

                ]);

                return redirect()->back()->with('success', 'Material uploaded successfully!');
            }
        } catch (\Exception $exe) {
            return redirect()->back()->with('error', 'Something Went Wrong' . $exe->getMessage());
        }
    }

    public function teacherResourceDelete($id)
    {
        $resourceDel = LessonResource::findorFail($id);

        $teacher = auth()->user()->teacher;
        $moduleId = $teacher->courses->pluck('id')->toArray();
        if (!in_array($resourceDel->lesson->module_id, $moduleId)) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }


        if ($resourceDel->file_path && Storage::disk('public')->exists($resourceDel->file_path)) {
            Storage::disk('public')->delete($resourceDel->file_path);
        }

        $resourceDel->delete();
        return redirect()->back()->with('success', 'Resource Deleted Successfully');
    }
}
