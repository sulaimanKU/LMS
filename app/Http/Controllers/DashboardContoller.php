<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardContoller
{

    public function dashboard_index()

    {

        return view('layouts.main_dashboard');

    }



}
