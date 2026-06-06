<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\OnlineClass;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Node\Expr\FuncCall;

use function Symfony\Component\Clock\now;

class AttendenceController
{
    public function attendence_view ()
    {
        $attendanceStats = OnlineClass::with('module')
            ->withCount('attendances')
            ->orderBy('class_date', 'desc')
            ->take(20)
            ->get();

        // Calculate some basic stats for the summary if needed
        // For now, we'll just pass the raw class data
        return view('layouts.attendence', compact('attendanceStats'));
    }


    public function logAttendanceAndJoin(Request $request, $id)
    {
        $user = Auth::user();
        $class = OnlineClass::with(['teacher.user', 'module'])->findOrFail($id);

        // 1. Allow Admins to join freely without logging attendance
        if ($user->hasRole('admin')) {
            return redirect()->away($class->meeting_link);
        }

        // 2. Allow the assigned Teacher to join freely without logging attendance
        if ($class->teacher && $class->teacher->user_id == $user->id) {
            return redirect()->away($class->meeting_link);
        }

        // 3. Check if the student is enrolled in this specific module
        $isEnrolled = \App\Models\Enrollment::where('user_id', $user->id)
            ->where('module_id', $class->module_id)
            ->where('status', 'active')
            .exists();

        if ($isEnrolled) {
            // Log attendance for the student
            Attendance::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'online_class_id' => $id,
                ],
                [
                    'joined_at' => now(),
                    'ip_address' => $request->ip()
                ]
            );
            return redirect()->away($class->meeting_link);
        }

        // 4. Deny access to everyone else
        return redirect()->back()->with('error', 'You are not authorized to join this class. Please ensure you are enrolled and your status is active.');
    }
}
