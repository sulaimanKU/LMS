<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController
{
    public function home_index() {

        return view('home_layouts.indexHome');


    }
    public function courses_index() {

       $courses = Courses::with('teacher')->get();

        // Pass the data to the view
        return view('home_layouts.coursesView', compact('courses'));

    }

    public function team_index()
    {
        $teachers = Teacher::with('courses')->get();
        return view('home_layouts.teamIndex', compact('teachers'));
    }
}
