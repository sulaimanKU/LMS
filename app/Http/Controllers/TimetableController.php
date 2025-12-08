<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class TimetableController
{
    public function index_timetable()
     {
        return view('layouts.timetable');
    }

    public function create_timetable_view()
    {
        return view('layouts.create_tb_view');
    }
}
