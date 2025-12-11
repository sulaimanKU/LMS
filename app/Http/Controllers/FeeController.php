<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeeController
{
    public function fee_view()
    {
        return view('layouts.fee_view');
    }
}
