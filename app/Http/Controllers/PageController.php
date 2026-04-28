<?php

namespace App\Http\Controllers;

use App\Models\Servis;

class PageController extends Controller
{
    public function welcome()
    {
        return view('welcome');
    }
}
