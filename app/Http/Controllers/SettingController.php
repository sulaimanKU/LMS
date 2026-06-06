<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index(){
        $user = Auth::user();
        $settings = SystemSetting::pluck('value', 'key')->toArray();
        return view('layouts.settings.settingView', compact('user', 'settings'));
    }

    public function updateSetting(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        if ($request->filled('password')){
            $request->validate([
                'password' => 'required|confirmed|min:8'
            ]);
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        return back()->with('success', 'Profile updated successfully!');
    }

    public function updateSystemSetting(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->except(['_token', 'site_logo_nav', 'site_logo_footer', 'site_favicon', 'home_poster']);

        foreach ($data as $key => $value) {
            SystemSetting::set($key, $value);
        }

        // Handle File Uploads
        $files = ['site_logo_nav', 'site_logo_footer', 'site_favicon', 'home_poster'];
        foreach ($files as $fileKey) {
            if ($request->hasFile($fileKey)) {
                // Delete old file if exists
                $oldPath = SystemSetting::get($fileKey);
                if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }

                $path = $request->file($fileKey)->store('system', 'public');
                SystemSetting::set($fileKey, $path);
            }
        }

        return back()->with('success', 'System settings updated successfully!');
    }
}
