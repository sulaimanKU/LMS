<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\OnlineClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class AssignmentController
{
    public function assignment_index()
    {
        $assignments = Assignment::with(['onlineClass.module', 'teacher'])
            ->withCount([
                'submissions',
                'submissions as pending_count' => fn($q) => $q->where('status', 'pending'),
                'submissions as graded_count'  => fn($q) => $q->where('status', 'graded'),
            ])
            ->latest()
            ->paginate(10);

        $totalAssignments = Assignment::count();
        $totalSubmissions = \App\Models\Submission::count();
        $pendingGrading   = \App\Models\Submission::where('status', 'pending')->count();
        $graded           = \App\Models\Submission::where('status', 'graded')->count();

        return view('layouts.assignments', compact(
            'assignments', 'totalAssignments', 'totalSubmissions', 'pendingGrading', 'graded'
        ));
    }
  public function index() {
    $user = auth()->user();
    $teacherRecord = Teacher::where('user_id', $user->id)->first();

    if (!$teacherRecord) {
        return view('teacherLayouts.assignmentsIndex', [
            'classes' => collect(),
            'assignments' => collect()
        ]);
    }


    $classes = OnlineClass::where('teacher_id', $teacherRecord->id)->get();


    // dd($classes);

    $assignments = Assignment::with('onlineClass')
                    ->withCount('submissions')
                    ->where('teacher_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

    return view('teacherLayouts.assignmentsIndex', compact('classes','assignments'));
}
    public function destroy($id)
    {
        $assignment = Assignment::where('id', $id)
            ->where('teacher_id', auth()->id())
            ->firstOrFail();
        $assignment->submissions()->delete();
        $assignment->delete();
        return redirect()->back()->with('success', 'Assignment deleted.');
    }

    public function teacherAssignmentsStore(Request $request)
    {
        $request->validate([
        'online_class_id' => 'required',
        'title' => 'required|string|max:255',
        'file' => 'nullable|mimes:pdf,docx,zip|max:5120', // Max 5MB
        'due_date' => 'required|after:now',
        'total_points' => 'required|integer|min:1',
    ]);

    $filePath = null;
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('assignments', 'public');
    }


    Assignment::create([
        'teacher_id' => auth()->id(),
        'online_class_id' => $request->online_class_id,
        'title' => $request->title,
        'description' => $request->description,
        'file_path' => $filePath,
        'total_points' => $request->total_points,
        'due_date' => $request->due_date,
    ]);

    return redirect()->back()->with('success', 'Assignment uploaded successfully!');
    }
}
