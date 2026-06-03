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

            foreach ($courseId as $id) {
                $check = Registration::where('email', $request->email)->whereJsonContains('selected_courses', (string)$id)->exists();
                if ($check) {
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

            $registerNow = Registration::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'institution' => $request->institution,
                'research_area' => $request->research_area,
                'selected_courses' => $saveNewId,
                'total_amount' => $calculateAllPrices,

            ]);

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
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

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

        // THIS return must be INSIDE the function
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

        public function logout(Request $request){
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('success','You logout Successfully');
        }

}
