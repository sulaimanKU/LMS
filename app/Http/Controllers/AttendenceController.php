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
        return view('layouts.attendence');
    }


    public function logAttendanceAndJoin(Request $request,$id)
    {
        $class = OnlineClass::findorFail($id);
       Attendance::updateOrCreate(
    [

        'user_id' => Auth::id(),
        'online_class_id' => $id,
    ],
    [

        'joined_at' => now(),
        'ip_address' => $request->ip()
    ]
);
        return redirect()->away($class->meeting_link);
    }
}
