<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return response()->json([
                'error' => 'Gemini API key is not configured'
            ], 500);
        }

        try {
            // Use models that support generateContent - with the 'models/' prefix
            // Try the latest stable models first
            $models = [
                'models/gemini-2.5-flash',      // Fast and efficient
                'models/gemini-2.5-pro',        // More capable
                'models/gemini-2.0-flash',      // Alternative
                'models/gemini-flash-latest',   // Latest flash
                'models/gemini-pro-latest',     // Latest pro
            ];

            $response = null;
            $lastError = null;

            // Use v1beta API (most common for these models)
            foreach ($models as $model) {
                // Remove 'models/' prefix for URL (API expects just the model name)
                $modelName = str_replace('models/', '', $model);
                $url = "https://generativelanguage.googleapis.com/v1beta/models/{$modelName}:generateContent?key={$apiKey}";

                try {
                    // Sewacare System Prompt
                    $sewacareSystemPrompt = "You are a helpful and professional AI assistant for Sewacare, a home medical service platform. Your role is to assist users with information about Sewacare's services and features.

RESPONSE STYLE:
- Keep responses SHORT and CONCISE (2-4 sentences maximum for simple questions, 1-2 short paragraphs for complex questions)
- Use bullet points or numbered lists when listing multiple items
- Be direct and to the point - avoid unnecessary elaboration
- Focus on answering the specific question asked

ABOUT SEWACARE:
Sewacare is a digital platform that provides accessible and reliable medical services directly at home. We connect patients with verified healthcare professionals including nurses, physiotherapists, and caregivers.

KEY SERVICES:
1. Home Nursing Care - Professional nursing services at home
2. Physiotherapy - Physical therapy sessions at your location
3. Caregiver Services - Verified caregivers for elderly, differently abled, or post-surgery care
4. Doctor Home Visits - Qualified doctors visiting patients at home
5. Medical Equipment - Equipment rental services
6. Video Consultations - Remote consultations with healthcare providers
7. Subscription Plans - Daily or monthly plans for elderly care, childcare, and maternity care

PLATFORM FEATURES:
- Verified Caregivers: All caregivers undergo comprehensive background verification
- AI-Powered Matching: Smart caregiver matching based on patient needs
- Location Tracking: Real-time tracking for safety
- Bidding System: Caregivers offer competitive rates for price comparison
- User Reviews: Read and leave reviews for caregivers
- Emergency SOS Button: Immediate assistance feature
- First Aid Information: Chatbot provides first aid guidance

IMPORTANT GUIDELINES:
- Always prioritize patient safety
- For medical emergencies, immediately direct users to call 911 or local emergency services
- Provide concise first aid information when asked, but remind users to consult healthcare professionals for serious concerns
- Keep responses brief and focused on the user's question
- Use lists when providing multiple points
- Be empathetic, professional, and clear

Remember: Keep responses SHORT and CONCISE. Answer directly without unnecessary details.";

                    $response = Http::timeout(30)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $sewacareSystemPrompt . "\n\nUser Question: " . $userMessage . "\n\nProvide a SHORT, CONCISE response (2-4 sentences or brief bullet points). Be direct and to the point."
                                    ]
                                ]
                            ]
                        ]
                    ]);

                    if ($response->successful()) {
                        Log::info('Successfully connected to Gemini API', [
                            'model' => $modelName,
                            'url' => $url
                        ]);
                        break; // Success!
                    } else {
                        $errorData = $response->json();
                        $errorMsg = $errorData['error']['message'] ?? 'Unknown error';

                        if ($response->status() !== 404) {
                            $lastError = $response;
                            Log::error('Non-404 error, stopping attempts', [
                                'status' => $response->status(),
                                'error' => $errorMsg,
                                'model' => $modelName
                            ]);
                            break;
                        }
                        $lastError = $response;
                    }
                } catch (\Exception $e) {
                    Log::warning('Exception trying model', [
                        'model' => $modelName,
                        'error' => $e->getMessage()
                    ]);
                    $lastError = $e;
                    continue;
                }
            }

            if (!$response || !$response->successful()) {
                if ($lastError instanceof \Exception) {
                    throw $lastError;
                }
                $response = $lastError;
            }

            if ($response->successful()) {
                $responseData = $response->json();

                if (isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                    $botResponse = $responseData['candidates'][0]['content']['parts'][0]['text'];
                } else {
                    Log::warning('Unexpected Gemini API response structure', ['response' => $responseData]);
                    $botResponse = 'Sorry, I could not generate a response. Please try again.';
                }

                return response()->json([
                    'response' => $botResponse,
                    'success' => true
                ]);
            } else {
                $errorBody = $response->json();
                $errorMessage = $errorBody['error']['message'] ?? 'API request failed';

                Log::error('Gemini API Error - All models failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'error' => $errorMessage
                ]);

                $userFriendlyError = 'Failed to connect to Gemini AI. ';
                if ($response->status() === 401) {
                    $userFriendlyError .= 'Invalid API key. Please verify your GEMINI_API_KEY in .env file.';
                } elseif ($response->status() === 404) {
                    $userFriendlyError .= 'Model not found. Please check the logs for details.';
                } else {
                    $userFriendlyError .= $errorMessage;
                }

                return response()->json([
                    'error' => $userFriendlyError,
                    'details' => $errorMessage
                ], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('Chat Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
