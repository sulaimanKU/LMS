<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\OnlineClass;
use App\Models\Teacher;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index() {
        return view('layouts.departments.departmentIndex');
    }

    public function classDep(Request $request)
    {
        OnlineClass::autoExpireLive();

        $filter  = $request->get('filter', 'all');
        $query   = OnlineClass::with(['module', 'teacher'])->orderByDesc('class_date')->orderByDesc('start_time');

        if ($filter !== 'all') {
            $query->where('status', $filter);
        }

        $classes   = $query->paginate(9)->withQueryString();
        $counts    = OnlineClass::selectRaw("status, COUNT(*) as total")->groupBy('status')->pluck('total', 'status');
        $courses   = Courses::orderBy('title')->get();
        $teachers  = Teacher::orderBy('name')->get();

        return view('layouts.departments.classDep', compact('classes', 'counts', 'courses', 'teachers', 'filter'));
    }

    public function classStore(Request $request)
    {
        $request->validate([
            'module_id'        => 'required|exists:modules,id',
            'teacher_id'       => 'required|exists:teachers,id',
            'title'            => 'required|string|max:255',
            'class_date'       => 'required|date',
            'start_time'       => 'required',
            'meeting_link'     => 'required|url',
            'duration'         => 'nullable|integer|min:1',
            'meeting_id'       => 'nullable|string|max:100',
            'meeting_password' => 'nullable|string|max:100',
            'description'      => 'nullable|string|max:500',
        ]);

        OnlineClass::create([
            'module_id'        => $request->module_id,
            'teacher_id'       => $request->teacher_id,
            'title'            => $request->title,
            'class_date'       => $request->class_date,
            'start_time'       => $request->start_time,
            'meeting_link'     => $request->meeting_link,
            'duration'         => $request->duration,
            'meeting_id'       => $request->meeting_id,
            'meeting_password' => $request->meeting_password,
            'description'      => $request->description,
            'status'           => 'upcoming',
        ]);

        return redirect()->back()->with('success', 'Class scheduled successfully.');
    }

    public function classDelete($id)
    {
        OnlineClass::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Class removed.');
    }

    public function subjectDep(){
        return view('layouts.departments.subjectDep');
    }
    public function virtualRoom()
    {
        OnlineClass::autoExpireLive();

        $liveClasses = OnlineClass::with(['module', 'teacher'])
            ->where('status', 'live')
            ->withCount(['attendances'])
            ->orderByDesc('updated_at')
            ->get();

        $totalLive     = $liveClasses->count();
        $totalStudents = $liveClasses->sum('attendances_count');

        // All non-live upcoming classes for today
        $upcomingToday = OnlineClass::with(['module', 'teacher'])
            ->where('status', 'upcoming')
            ->whereDate('class_date', today())
            ->orderBy('start_time')
            ->get();

        return view('layouts.virtualRoom.virtualRoom', compact(
            'liveClasses', 'totalLive', 'totalStudents', 'upcomingToday'
        ));
    }
}
