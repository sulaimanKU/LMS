<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Courses;
use App\Models\Registration;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Mail\StudentApprovedMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class DashboardController
{

    public function dashboard_index()
    {
        $totalStudents      = User::role('student')->count();
        $totalTeachers      = Teacher::count();
        $totalCourses       = Courses::count();
        $pendingCount       = Registration::where('status', 'pending')->count();
        $recentRegistrations = Registration::with('slips')->latest()->take(6)->get();

        // Monthly registrations for the last 6 months
        $monthlyData = Registration::selectRaw("DATE_FORMAT(created_at, '%b') as month, COUNT(*) as total")
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupByRaw("DATE_FORMAT(created_at, '%b %Y'), DATE_FORMAT(created_at, '%b')")
            ->orderBy('created_at')
            ->pluck('total', 'month');

        return view('dashboard.admin', compact(
            'totalStudents',
            'totalTeachers',
            'totalCourses',
            'pendingCount',
            'recentRegistrations',
            'monthlyData'
        ));
    }


    public function teacherMangmentView()
    {
        $courses  = Courses::orderBy('title')->get();
        $teachers = Teacher::with('courses')->get();
        return view('layouts.teacherMangment', compact('courses', 'teachers'));
    }



 public function teacherRegister(Request $request) {

    $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|unique:teachers,email|unique:users,email',
        'designation'   => 'required|string|max:255',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'bio'           => 'nullable|string|max:1000',
        'course_id'     => 'nullable|array',
        'course_id.*'   => 'integer|exists:modules,id',
    ]);

    DB::beginTransaction();
    try {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make('12345678'),
        ]);

        $user->assignRole('teacher');

        $file_name = null;
        if ($request->hasFile('profile_image')) {
            // Generate a clean filename
            $file_name = time() . '_' . str_replace(' ', '_', $request->name)
                       . '.' . $request->profile_image->extension();

            /** * CHANGE MADE HERE:
             * Using storeAs with the 'public' disk.
             * This saves to: storage/app/public/teachers/filename.png
             */
            $request->file('profile_image')->storeAs('teachers', $file_name, 'public');
        }

        $teacher = Teacher::create([
            'user_id'         => $user->id,
            'name'            => $request->name,
            'email'           => $request->email,
            'designation'     => $request->designation,
            'profile_image'   => $file_name, // Saves only the filename
            'scopus_link'     => $request->scopus_link,
            'bio'             => $request->bio,
            'specialization'  => $request->specialization,
            'linkedin_url'    => $request->linkedin_url,
        ]);

        if ($request->filled('course_id')) {
            $teacher->courses()->attach($request->course_id);
        }

        DB::commit();
        return redirect()->back()->with('success', 'Teacher registered successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
    }
}











public function teacherUpdate(Request $request, $id)
{
    $teacher = Teacher::findOrFail($id);

    $request->validate([
        'name'          => 'required|string|max:255',
        'email'         => 'required|email|unique:teachers,email,' . $teacher->id . '|unique:users,email,' . $teacher->user_id,
        'designation'   => 'required|string|max:255',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'bio'           => 'nullable|string|max:1000',
    ]);

    $file_name = $teacher->profile_image;

    if ($request->hasFile('profile_image')) {

        // 1. DELETE THE OLD IMAGE FROM STORAGE
        if ($file_name && Storage::disk('public')->exists('teachers/' . $file_name)) {
            Storage::disk('public')->delete('teachers/' . $file_name);
        }

        // 2. PREPARE NEW FILENAME
        $file_name = time() . '_' . str_replace(' ', '_', $request->name)
                   . '.' . $request->profile_image->extension();

        // 3. STORE NEW IMAGE IN STORAGE FOLDER
        $request->file('profile_image')->storeAs('teachers', $file_name, 'public');
    }

    $teacher->update([
        'name'           => $request->name,
        'email'          => $request->email,
        'designation'    => $request->designation,
        'profile_image'  => $file_name,
        'scopus_link'    => $request->scopus_link,
        'bio'            => $request->bio,
        'specialization' => $request->specialization,
        'linkedin_url'   => $request->linkedin_url,
    ]);

    // Sync the linked user name/email too
    if ($teacher->user_id) {
        User::where('id', $teacher->user_id)->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);
    }

    return redirect()->back()->with('success', $teacher->name . ' updated successfully!');
}

public function teacherDelete($id)
{
    $teacher = Teacher::findOrFail($id);

    DB::transaction(function () use ($teacher) {
        // Detach all course assignments
        $teacher->courses()->detach();

        // Remove profile image
        if ($teacher->profile_image && file_exists(public_path('uploads/teachers/' . $teacher->profile_image))) {
            unlink(public_path('uploads/teachers/' . $teacher->profile_image));
        }

        // Delete linked user account
        if ($teacher->user_id) {
            User::where('id', $teacher->user_id)->delete();
        }

        $teacher->delete();
    });

    return redirect()->back()->with('success', 'Teacher deleted successfully.');
}

public function teacherAssignCourse(Request $request, $id)
{
    $request->validate(['course_id' => 'required|integer|exists:modules,id']);

    $teacher = Teacher::findOrFail($id);
    $alreadyAssigned = $teacher->courses()->where('module_id', $request->course_id)->exists();

    if ($alreadyAssigned) {
        return redirect()->back()->with('error', 'This module is already assigned to ' . $teacher->name . '.');
    }

    $teacher->courses()->attach($request->course_id);

    return redirect()->back()->with('success', 'Module assigned to ' . $teacher->name . ' successfully!');
}

public function studentManagment(Request $request)
{
    $filter = $request->get('filter', 'all');
    $query  = Registration::with('slips')->latest();
    if ($filter !== 'all') {
        $query->where('status', $filter);
    }
    $registrations = $query->paginate(9)->withQueryString();
    $allCourses    = Courses::orderBy('title')->get()->keyBy('id');

    // For each registration email, find which module IDs are actually enrolled with their status
    $emails = $registrations->pluck('email')->unique()->filter();
    $enrolledByEmail = User::whereIn('email', $emails)
        ->with('enrolledModules')
        ->get()
        ->keyBy('email')
        ->map(fn($u) => $u->enrolledModules->keyBy('id'));

    return view('layouts.studentManagment', compact('registrations', 'allCourses', 'filter', 'enrolledByEmail'));
}

public function adminApproveStudent(Request $request, $id)
{
    $reg = Registration::findOrFail($id);

    $request->validate([
        'approved_courses'   => 'required|array|min:1',
        'approved_courses.*' => 'integer',
    ], [
        'approved_courses.required' => 'Please select at least one module to approve.',
        'approved_courses.min'      => 'Please select at least one module to approve.',
    ]);

    // Keep only IDs that were in the original registration (prevents tampering)
    $validIds    = array_map('intval', $reg->selected_courses ?? []);
    $approvedIds = array_values(array_filter(
        array_map('intval', $request->approved_courses),
        fn($cid) => in_array($cid, $validIds)
    ));

    if (empty($approvedIds)) {
        return redirect()->back()->withErrors(['approved_courses' => 'Selected modules are not valid for this registration.']);
    }
    $plainPassword  = Str::random(8);
    $isExistingUser = User::where('email', $reg->email)->exists();

    return DB::transaction(function () use ($reg, $approvedIds, $plainPassword, $isExistingUser) {

        $user = User::firstOrCreate(
            ['email' => $reg->email],
            ['name' => $reg->name, 'password' => Hash::make($plainPassword)]
        );

        if (!$user->hasRole('student')) {
            $user->assignRole('student');
        }

        $pivotData = array_fill_keys($approvedIds, ['status' => 'active']);
        $user->enrolledModules()->syncWithoutDetaching($pivotData);

        $reg->update([
            'status'      => 'approved',
            'approved_at' => Carbon::now(),
        ]);
        $reg->slips()->update(['status' => 'approved']);

        $enrolledCourseNames = Courses::whereIn('id', $approvedIds)->pluck('title')->toArray();

        if (!$isExistingUser) {
            // New account — send full credentials with password
            $emailSent = true;
            $errorDetail = '';
            try {
                Mail::to($reg->email)->send(new StudentApprovedMail(
                    $reg->name, $reg->email, $plainPassword, $enrolledCourseNames
                ));
            } catch (\Exception $e) {
                $emailSent = false;
                $errorDetail = $e->getMessage();
                \Log::error('Approval email failed for ' . $reg->email . ': ' . $errorDetail);
            }

            if ($emailSent) {
                $msg = "{$reg->name} approved and enrolled. Login credentials sent to {$reg->email}.";
            } else {
                $msg = "{$reg->name} approved, but the welcome EMAIL FAILED to send. Error: {$errorDetail}. Please check your mail settings.";
                return redirect()->back()->with('warning', $msg);
            }
        } else {
            // Existing account — enroll only, no password resent
            $msg = "{$reg->name} enrolled in " . count($approvedIds) . " additional module(s). Account already exists — credentials NOT resent.";
        }

        return redirect()->back()->with('success', $msg);
    });
}

public function deleteRegistration($id)
{
    $reg = Registration::findOrFail($id);
    
    DB::beginTransaction();
    try {
        // 1. Find and delete the associated user if they exist
        $user = User::where('email', $reg->email)->first();
        if ($user) {
            // Delete associated files if needed (profile images, etc.)
            $user->delete();
        }

        // 2. Delete the registration (cascades or manual cleanup for slips/enrollments depends on your DB setup)
        // Usually, slips and pivot records should have onDelete('cascade') in migrations.
        $reg->delete();

        DB::commit();
        return redirect()->back()->with('success', 'Registration and associated user account deleted successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Error deleting registration: ' . $e->getMessage());
    }
}

public function systemAdminsView()
{
    $admins = User::role('admin')->orderBy('created_at', 'desc')->get();
    return view('layouts.systemAdmins', compact('admins'));
}

public function systemAdminStore(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
    ]);

    $user->assignRole('admin');

    return redirect()->back()->with('success', "{$user->name} has been added as an admin successfully.");
}

public function systemAdminDelete($id)
{
    if ($id == auth()->id()) {
        return redirect()->back()->with('error', 'You cannot remove your own admin account.');
    }

    $user = User::findOrFail($id);

    if (!$user->hasRole('admin')) {
        return redirect()->back()->with('error', 'This user is not an admin.');
    }

    $user->removeRole('admin');

    return redirect()->back()->with('success', "{$user->name} has been removed as admin. Their account still exists but without admin access.");
}

public function roleOrPermissionsView ()
{
    $roles = Role::all();
    $permissions = Permission::paginate(10);
    return view('layouts.adminRoles' , compact('roles','permissions'));
}

public function updateEnrollmentStatus(Request $request)
{
    if (!auth()->user()->can('manage-enrollments')) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'email' => 'required|email|exists:users,email',
        'module_id' => 'required|exists:modules,id',
        'status' => 'required|in:active,completed,dropped',
    ]);

    $user = User::where('email', $request->email)->firstOrFail();
    
    $user->enrolledModules()->updateExistingPivot($request->module_id, [
        'status' => $request->status
    ]);

    return back()->with('success', 'Enrollment status updated successfully.');
}

public function adminUpdateUserPassword(Request $request)
{
    if (!auth()->user()->can('manage-user-passwords')) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = User::where('email', $request->email)->firstOrFail();
    
    $user->update([
        'password' => Hash::make($request->password)
    ]);

    return back()->with('success', "Password for {$user->name} has been updated successfully.");
}

}



