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
        $validate = $request->validate([

                'email'=> 'required|email|exists:registrations,email',
                'slip' => 'required|image|mimes:jpg,jpeg,png,webp,pdf|max:4096'

        ]);
        try{



        $checkRegistration = Registration::where('email', $request->email)->latest()->first();
        if(!$checkRegistration)
        {
            return back()->withErrors(['error'=> 'we could not find registeration with that email.']);
        }
        if ($request->hasFile('slip'))
        {
            $savePath = $request->file('slip')->store('paymentSlips','public');

            $saveSlip = PaymentSlip::create([

                    'registration_id' => $checkRegistration->id,
                    'file_path' => $savePath,
                    'status' => 'pending'


                ]);
                return redirect()->back()->with('success','Your Payment slip Uploaded successfully . After verification we will send your username and password through email and you also Track your status.Thank You ');
        }
    }catch(\Exception $exe){
        return back()->withErrors(['error' => 'Something Went Wrong :' .$exe->getMessage()]);

}
}
}
