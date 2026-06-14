<?php

namespace App\Http\Controllers;

use App\Models\PaymentSlip;
use App\Models\Registration;
use Illuminate\Http\Request;

class PaymentController
{

    public function uploadPaymentView ()
    {
        return view('home_layouts.paymentUploadView');
    }

    public function uploadPayment (Request $request)
    {
        // Trim email to avoid accidental spaces causing lookup failure
        $request->merge(['email' => trim($request->email)]);

        $validate = $request->validate([
            'email'=> 'required|email|exists:registrations,email',
            'slip' => 'required|mimes:jpg,jpeg,png,webp,pdf|max:4096' // Removed 'image' as it blocks PDFs
        ]);

        try {
            $checkRegistration = Registration::where('email', $request->email)->latest()->first();
            
            if (!$checkRegistration) {
                return back()->withInput()->withErrors(['error'=> 'We could not find a registration with that email. Please ensure you have registered first.']);
            }

            if ($request->hasFile('slip')) {
                $savePath = $request->file('slip')->store('paymentSlips', 'public');

                $saveSlip = PaymentSlip::create([
                    'registration_id' => $checkRegistration->id,
                    'file_path' => $savePath,
                    'status' => 'pending'
                ]);

                return redirect()->back()->with('success', 'Your payment slip was uploaded successfully. After verification, we will send your username and password through email. You can also track your status on the home page. Thank you!');
            }
        } catch (\Exception $exe) {
            \Log::error('Payment Slip Upload Error: ' . $exe->getMessage());
            return back()->withInput()->withErrors(['error' => 'Something went wrong while uploading: ' . $exe->getMessage()]);
        }
    }
}
