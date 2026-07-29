<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $settings = SystemSetting::pluck('value', 'key')->toArray();

        // Active mail settings from env or system_settings
        $mailSettings = [
            'mail_mailer'       => $settings['mail_mailer'] ?? env('MAIL_MAILER', 'smtp'),
            'mail_host'         => $settings['mail_host'] ?? env('MAIL_HOST', 'mail.tread.com.pk'),
            'mail_port'         => $settings['mail_port'] ?? env('MAIL_PORT', '465'),
            'mail_username'     => $settings['mail_username'] ?? env('MAIL_USERNAME', ''),
            'mail_password'     => $settings['mail_password'] ?? env('MAIL_PASSWORD', ''),
            'mail_encryption'   => $settings['mail_encryption'] ?? env('MAIL_ENCRYPTION', 'ssl'),
            'mail_from_address' => $settings['mail_from_address'] ?? env('MAIL_FROM_ADDRESS', ''),
            'mail_from_name'    => $settings['mail_from_name'] ?? env('MAIL_FROM_NAME', config('app.name')),
        ];

        return view('layouts.settings.settingView', compact('user', 'settings', 'mailSettings'));
    }

    public function updateSetting(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:500',
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

        if ($request->filled('password')) {
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

    public function updateEmailSetting(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'mail_mailer'       => 'required|string',
            'mail_host'         => 'required|string',
            'mail_port'         => 'required|numeric',
            'mail_username'     => 'nullable|string',
            'mail_password'     => 'nullable|string',
            'mail_encryption'   => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name'    => 'required|string',
        ]);

        $enc = strtolower($request->mail_encryption);
        if ($enc === 'none' || $enc === 'null') {
            $encVal = '';
            $envEnc = 'null';
        } else {
            $encVal = $enc;
            $envEnc = $enc;
        }

        $data = [
            'mail_mailer'       => $request->mail_mailer,
            'mail_host'         => $request->mail_host,
            'mail_port'         => $request->mail_port,
            'mail_username'     => $request->mail_username ?? '',
            'mail_password'     => $request->mail_password ?? '',
            'mail_encryption'   => $encVal,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name'    => $request->mail_from_name,
        ];

        foreach ($data as $key => $val) {
            SystemSetting::set($key, $val);
        }

        $envUpdates = [
            'MAIL_MAILER'       => $data['mail_mailer'],
            'MAIL_HOST'         => $data['mail_host'],
            'MAIL_PORT'         => $data['mail_port'],
            'MAIL_USERNAME'     => $data['mail_username'],
            'MAIL_PASSWORD'     => $data['mail_password'],
            'MAIL_ENCRYPTION'   => $envEnc,
            'MAIL_FROM_ADDRESS' => $data['mail_from_address'],
            'MAIL_FROM_NAME'    => $data['mail_from_name'],
        ];

        $this->setEnvKeys($envUpdates);

        return back()->with('success', 'Email / SMTP configurations saved successfully in system settings & .env file!');
    }

    public function sendTestEmail(Request $request)
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $mailer     = env('MAIL_MAILER', 'smtp');
            $host       = env('MAIL_HOST', 'mail.tread.com.pk');
            $port       = env('MAIL_PORT', '465');
            $encryption = env('MAIL_ENCRYPTION');
            $username   = env('MAIL_USERNAME');
            $password   = env('MAIL_PASSWORD');
            $fromAddr   = env('MAIL_FROM_ADDRESS');
            $fromName   = env('MAIL_FROM_NAME');

            config([
                'mail.default'                 => $mailer,
                'mail.mailers.smtp.host'       => $host,
                'mail.mailers.smtp.port'       => $port,
                'mail.mailers.smtp.encryption' => ($encryption === 'null' || !$encryption) ? null : $encryption,
                'mail.mailers.smtp.username'   => $username,
                'mail.mailers.smtp.password'   => $password,
                'mail.from.address'            => $fromAddr,
                'mail.from.name'               => $fromName,
            ]);

            Mail::raw("Hello! This is a test email sent from your LMS Email Configuration settings at " . now()->format('Y-m-d H:i:s') . ". Your SMTP configuration is working properly!", function ($message) use ($request, $fromAddr, $fromName) {
                $message->to($request->test_email)
                        ->from($fromAddr, $fromName)
                        ->subject("LMS SMTP Connection Test Success");
            });

            return back()->with('success', "Test email successfully sent to {$request->test_email}! Your SMTP setup is working properly.");
        } catch (\Exception $e) {
            return back()->with('error', "SMTP Connection Error: " . $e->getMessage());
        }
    }

    protected function setEnvKeys(array $data)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            $key = strtoupper($key);
            
            if ($value === 'null' || $value === null) {
                $formattedValue = 'null';
            } elseif (str_contains($value, ' ') || str_contains($value, '#') || str_contains($value, '&') || str_contains($value, '"') || str_contains($value, "'") || str_contains($value, '[')) {
                $formattedValue = '"' . str_replace('"', '\"', $value) . '"';
            } else {
                $formattedValue = $value;
            }

            if (preg_match("/^{$key}=.*/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$formattedValue}", $envContent);
            } else {
                $envContent .= "\n{$key}={$formattedValue}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
