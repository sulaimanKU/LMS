<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssignmentController
{
    public function assignment_index()

    {
        return view('layouts.assignments');
    }
}
