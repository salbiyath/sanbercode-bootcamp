<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;

class TestController extends Controller
{
    public function verifyEmail()
    {
        return "Hello user, your account verified";
    }
    public function verifyAdmin()
    {
        return "Hello admin, your account verified";
    }
}
