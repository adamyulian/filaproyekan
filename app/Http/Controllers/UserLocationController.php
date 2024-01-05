<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class UserLocationController extends Controller
{
    public function userLocation()
    {
        $ip = request()->ip();
        $data = Location::get($ip);
        return compact ('data');
    }
}
