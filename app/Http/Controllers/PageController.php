<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home() {
        return view('home');
    }

    public function services() {
        return view('services');
    }

    public function bookNow() {
        return view('book');
    }

    public function faq() {
        return view('faq');
    }
}


