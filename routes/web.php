<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AttendenceController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\DashboardContoller;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\TimetableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;


Route::get('/',[HomeController::class,'home_index'])->name('home');


Route::get('/dashboard',[DashboardContoller::class,'dashboard_index'])->name('dashboard');

Route::get('courses',[CoursesController::class,'courses_index'])->name('course.index');
Route::get('assignments',[AssignmentController::class,'assignment_index'])->name('assignments');
Route::get('timetable',[TimetableController::class,'index_timetable'])->name('time.table');
Route::get('create/timetable',[TimetableController::class,'create_timetable_view'])->name('create.timetable.view');
Route::get('attendence/view',[AttendenceController::class,'attendence_view'])->name('attendence.view');
Route::get('fees',[FeeController::class,'fee_view'])->name('fee.view');


