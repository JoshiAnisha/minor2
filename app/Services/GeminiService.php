<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected $apiKey;
    protected $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY'); // Make sure this is in your .env
    }

    public function generateReply($userMessage)
    {
        $response = Http::post($this->endpoint . '?key=' . $this->apiKey, [
            'contents' => [[
                'parts' => [[ 'text' => $userMessage ]]
            ]]
        ]);

        if ($response->successful()) {
            return $response['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';
        }

        return 'Something went wrong.';
    }
}
