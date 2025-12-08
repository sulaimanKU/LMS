<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController
{
    public function home_index() {

        return view('welcome');


    }
}
