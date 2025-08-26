<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CropController extends Controller
{
    public function getSuggestion() {
      return view('getSuggestion');
    }

    public function recommendCrop(Request $request)
{
    $data = $request->validate([
        'soil_type' => 'required|string',
        'area' => 'required|numeric',
        'location' => 'nullable|string',
        'previous_crop' => 'nullable|string',
        'notes' => 'nullable|string',
    ]);

    // Build prompt
    $prompt = "You are an agricultural advisor. Recommend 3 best crops to plant given these farm details:\n";
    foreach ($data as $k => $v) {
        $prompt .= ucfirst($k) . ": " . ($v ?? 'N/A') . "\n";
    }
    $prompt .= "\nReturn in plain text in bangla.";

    

    // Call Gemini API
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
        'X-goog-api-key' => env('GEMINI_API_KEY'),
    ])->post(env('GEMINI_API_URL'), [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ]
    ]);

    if ($response->failed()) {
        return response()->json(['error' => 'AI service failed: ' . $response->body()], 500);
    }

    $body = $response->json();
    $suggestions = $body['candidates'][0]['content']['parts'][0]['text'] ?? 'No suggestions returned';

    return response()->json(['suggestions' => $suggestions]);
}
}

