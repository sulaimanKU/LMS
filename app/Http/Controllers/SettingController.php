<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('layouts.settings.settingView',compact('user'));
    }

    public function updateSetting(Request $request)
    {
        $userId = Auth::id();

        $user = Auth::user($userId);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')){
            $request->validate([
                'password' => 'required|confirmed|min:8'
            ]);
          $user->password = Hash::make($request->password);
        }
       $user->save();
        return back()->with('success', 'Profile updated successfully!');
    }
}
