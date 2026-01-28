<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GeminiService;

class GeminiController extends Controller
{
    public function chat(Request $request, GeminiService $gemini)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $response = $gemini->generateReply($request->message);

        return response()->json(['reply' => $response]);
    }
}
