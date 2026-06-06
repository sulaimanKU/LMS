<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PaymentSlip;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;

class FeeController
{
    public function fee_view()
    {
        $recentTransactions = PaymentSlip::with('registration')
            ->latest()
            ->take(10)
            ->get();

        $totalCollected = Registration::where('status', 'approved')->sum('total_amount');
        $outstanding = Registration::where('status', '!=', 'approved')->sum('total_amount');
        
        $totalPotential = $totalCollected + $outstanding;
        $collectionRate = $totalPotential > 0 ? round(($totalCollected / $totalPotential) * 100) : 0;

        $systemLogs = DB::table('payment_slips')
            ->join('registrations', 'payment_slips.registration_id', '=', 'registrations.id')
            ->select('payment_slips.*', 'registrations.name as student_name')
            ->latest('payment_slips.created_at')
            ->take(5)
            ->get();

        return view('layouts.fee_view', compact(
            'recentTransactions', 
            'totalCollected', 
            'outstanding', 
            'collectionRate',
            'systemLogs'
        ));
    }
}
