<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendenceController
{
    public function attendence_view ()
    {
        return view('layouts.attendence');
    }
}
