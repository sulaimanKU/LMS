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
        $assignments = Assignment::with(['module', 'teacher'])
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
                'myModules' => collect(),
                'assignments' => collect()
            ]);
        }

        // Get all Modules assigned to this teacher
        $myModules = $teacherRecord->courses()->get();
        $assignedModuleIds = $myModules->pluck('id')->toArray();

        $assignments = Assignment::with(['module', 'teacher'])
                        ->withCount('submissions')
                        ->whereIn('module_id', $assignedModuleIds)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('teacherLayouts.assignmentsIndex', compact('myModules','assignments'));
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
        'module_id' => 'required|exists:modules,id',
        'title' => 'required|string|max:255',
        'file' => 'nullable|mimes:pdf,docx,zip,jpg,jpeg,png,ppt,pptx|max:5120', // Max 5MB
        'due_date' => 'required|after:now',
        'total_points' => 'required|integer|min:1',
    ]);

    $filePath = null;
    if ($request->hasFile('file')) {
        $filePath = $request->file('file')->store('assignments', 'public');
    }


    Assignment::create([
        'teacher_id' => auth()->id(),
        'module_id' => $request->module_id,
        'title' => $request->title,
        'description' => $request->description,
        'file_path' => $filePath,
        'total_points' => $request->total_points,
        'due_date' => $request->due_date,
    ]);

    return redirect()->back()->with('success', 'Assignment uploaded successfully!');
    }
}
