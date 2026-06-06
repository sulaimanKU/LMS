<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;

class HomeController
{
    public function home_index() {
        $stats = [
            'students' => User::role('student')->count(),
            'courses' => Courses::where('status', 'active')->count(),
            'teachers' => Teacher::count(),
            'satisfaction' => 98, // You can make this dynamic if you have a reviews table
        ];

        $popularCourses = Courses::withCount('enrollments')
            ->with(['teacher', 'lessons'])
            ->where('status', 'active')
            ->orderBy('enrollments_count', 'desc')
            ->take(3)
            ->get();

        $reviews = Review::where('status', 'active')->latest()->take(6)->get();

        return view('home_layouts.indexHome', compact('stats', 'popularCourses', 'reviews'));
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
