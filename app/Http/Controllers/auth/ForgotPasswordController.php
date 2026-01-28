<?php

namespace App\Http\Controllers\Auth;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    public function forgotpassword()
    {
        return view('auth.forgotpassword');
    }
}
