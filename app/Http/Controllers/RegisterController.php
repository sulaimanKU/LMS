<?php

namespace App\Http\Controllers;

use App\Models\Courses;
use App\Models\Registration;
use App\Models\User;
use Exception;
// use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController
{
    public function register_index()
    {
        $courses = Courses::with('teacher')->get();
        return view('home_layouts.registration', compact('courses'));
    }

    public function register_store(Request $request)
    {
        $validate = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|max:255',
            'institution'      => 'required|string|max:255',
            'research_area'    => 'nullable|string|max:255',
            'phone'            => 'required|string|max:30',
            'selected_courses' => 'required|array|min:1',
            'selected_courses.*' => 'integer|exists:modules,id',
        ], [
            'selected_courses.required' => 'Please select at least one module to continue.',
            'selected_courses.min'      => 'Please select at least one module to continue.',
        ]);

        try {
            $courseId = $request->selected_courses;
            $saveNewId = [];
            $alreadyExistId = [];

            // Find existing user to check their current enrollments
            $existingUser = User::where('email', $request->email)->with('enrolledModules')->first();
            $enrolledIds = $existingUser ? $existingUser->enrolledModules->pluck('id')->toArray() : [];

            foreach ($courseId as $id) {
                // Check registrations table
                $regCheck = Registration::where('email', $request->email)->whereJsonContains('selected_courses', (string)$id)->exists();
                // Check actual enrollments table
                $enrollCheck = in_array($id, $enrolledIds);

                if ($regCheck || $enrollCheck) {
                    $courses = Courses::find($id);
                    $alreadyExistId[] = $courses->title;
                } else {
                    $saveNewId[] = $id;
                }
            }
            if (empty($saveNewId)) {
                return back()->withInput()->withErrors([
                    'error' => 'you have already registered for the all the selected courses' . implode(',', $alreadyExistId)
                ]);
            }
            $calculateAllPrices = Courses::whereIn('id', $saveNewId)->sum('price');

            // Find existing registration to merge or create new
            $existingReg = Registration::where('email', $request->email)->first();

            if ($existingReg) {
                // Merge courses
                $currentCourses = $existingReg->selected_courses ?? [];
                $mergedCourses = array_unique(array_merge($currentCourses, $saveNewId));
                
                $existingReg->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'institution' => $request->institution,
                    'research_area' => $request->research_area,
                    'selected_courses' => $mergedCourses,
                    'total_amount' => $existingReg->total_amount + $calculateAllPrices,
                    'status' => 'pending' // Reset to pending for new modules
                ]);
                $registerNow = $existingReg;
            } else {
                $registerNow = Registration::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'institution' => $request->institution,
                    'research_area' => $request->research_area,
                    'selected_courses' => $saveNewId,
                    'total_amount' => $calculateAllPrices,
                    'status' => 'pending'
                ]);
            }

            if (!empty($alreadyExistId)) {

                return redirect()->route('registration.success',$registerNow->id)->with("success", "Registeration Successful but please Note :" . implode(',', $alreadyExistId) . " were skiped as you already registered for them.");
            }
            return redirect()->route('registration.success',$registerNow->id)->with('success', 'you regester successfully further pay cash with provided bank account and upload the slip .After Verification we will inform you');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'something went wrong:' . $e->getMessage()]);
        }
    }


    public function registerationSuccess($id)
    {
        $registeration = Registration::findorFail($id);
        $coursesDetial = Courses::whereIn('id',$registeration->selected_courses)->get();
        return view('home_layouts.registrationSuccess',compact('registeration','coursesDetial'));
    }
    public function trackRegisteration(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $trackReg = Registration::where('email', $request->email)
            ->with('slips')
            ->latest()
            ->get();

        if ($trackReg->isEmpty()) {
            return back()->withErrors(['error' => 'No registration found for that email address.']);
        }

        $allCourses = Courses::get()->keyBy('id');

        $enrolledIds = User::where('email', $request->email)
            ->with('enrolledModules:id')
            ->first()?->enrolledModules->pluck('id')->toArray() ?? [];

        return view('home_layouts.trackRegisteration', compact('trackReg', 'allCourses', 'enrolledIds'));
    }

    public function loginView()
    {
        return view('logFile.loginView');
    }

    public function submitLogin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // 1. Check account status if set
            if (isset($user->status) && strtolower($user->status) === 'inactive') {
                Auth::logout();
                $request->session()->invalidate();
                return back()->withErrors(['email' => 'Your account is currently inactive. Please contact administration.']);
            }

            // 2. If student, check registration approval status
            if ($user->hasRole('student')) {
                $reg = \App\Models\Registration::where('email', $user->email)->latest()->first();
                if ($reg) {
                    if ($reg->status === 'pending') {
                        Auth::logout();
                        $request->session()->invalidate();
                        return back()->withErrors(['email' => 'Your registration is still pending admin approval. You will be able to log in once approved.']);
                    } elseif ($reg->status === 'rejected') {
                        Auth::logout();
                        $request->session()->invalidate();
                        return back()->withErrors(['email' => 'Your registration request was not approved. Please contact support.']);
                    }
                }
            }

            $request->session()->regenerate();

            if ($user->hasRole('admin')) {
                return redirect()->route('dashboard');
            }

            if ($user->hasRole('teacher')) {
                return redirect()->route('teacher.main');
            }

            if ($user->hasRole('student')) {
                return redirect()->route('student.main');
            }

            return redirect('/');
        }

        return back()->withErrors(['email' => 'Invalid email address or password.']);
    }

    public function forgotPasswordView()
    {
        return view('logFile.forgotPassword');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'We could not find an account with that email address.']);
        }

        if ($user->hasRole('student')) {
            $reg = \App\Models\Registration::where('email', $user->email)->latest()->first();
            if ($reg && $reg->status !== 'approved') {
                return back()->withErrors(['email' => 'Password reset is unavailable because your registration is pending approval.']);
            }
        }

        $token = \Illuminate\Support\Str::random(64);

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        try {
            $fromAddr = env('MAIL_FROM_ADDRESS', 'info@tread.com.pk');
            $fromName = env('MAIL_FROM_NAME', config('app.name'));

            \Illuminate\Support\Facades\Mail::raw(
                "Hello {$user->name},\n\nYou requested a password reset for your account on " . config('app.name') . ".\n\nPlease click or open the following link to set your new password:\n\n{$resetUrl}\n\nIf you did not request a password reset, please ignore this message.\n\nBest regards,\n" . config('app.name') . " Team",
                function ($message) use ($user, $fromAddr, $fromName) {
                    $message->to($user->email)
                            ->from($fromAddr, $fromName)
                            ->subject("Password Reset Link — " . config('app.name'));
                }
            );

            return back()->with('success', 'A password reset link has been sent to your email address. Please check your inbox.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send reset email: ' . $e->getMessage()]);
        }
    }

    public function resetPasswordView(Request $request, $token)
    {
        $email = $request->get('email');
        return view('logFile.resetPassword', compact('token', 'email'));
    }

    public function submitResetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $record = \Illuminate\Support\Facades\DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Invalid or expired password reset token. Please request a new link.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully! You can now log in.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'You logged out successfully.');
    }

}
