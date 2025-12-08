<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoursesController
{
   public function courses_index()

   {
    return view('layouts.courses');
   }
}
