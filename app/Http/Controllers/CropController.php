<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CropController extends Controller
{
    /**
     * Show crop suggestion form
     */
    public function getSuggestion()
    {
        return view('getSuggestion');
    }

    /**
     * Recommend suitable crops using Gemini AI
     */
    public function recommendCrop(Request $request)
    {
        // ✅ Validate input
        $data = $request->validate([
            'soil_type'     => 'required|string|max:100',
            'area'          => 'required|numeric|min:0.1',
            'location'      => 'nullable|string|max:150',
            'previous_crop' => 'nullable|string|max:150',
            'notes'         => 'nullable|string|max:500',
        ]);

        // ✅ Build improved prompt safely
        $location      = !empty($data['location']) ? $data['location'] : 'উল্লেখ নেই';
        $previousCrop  = !empty($data['previous_crop']) ? $data['previous_crop'] : 'উল্লেখ নেই';
        $notes         = !empty($data['notes']) ? $data['notes'] : 'কোনো নোট নেই';

        $prompt = <<<EOT
আসসালামু আলাইকুম। আপনি একজন কৃষি বিশেষজ্ঞ (Agricultural Advisor)। নিচের খামারের তথ্য অনুযায়ী ৩টি সবচেয়ে উপযুক্ত ফসলের নাম এবং কেন এগুলো উপযুক্ত তা বাংলায় পরামর্শ দিন। 
প্রতিটি ফসলের জন্য সম্ভাব্য ফলন ও সাধারণ যত্নের টিপসও সংক্ষেপে দিন। ধাপে ধাপে ও সহজ ভাষায় লিখুন। 

⚠️ নোট: এটি সাধারণ তথ্য, চূড়ান্ত সিদ্ধান্তের আগে স্থানীয় কৃষি অফিসারের সাথে যাচাই করুন। 

খামারের তথ্য:
মাটির ধরন: {$data['soil_type']}
জমির পরিমাণ (একর/হেক্টর): {$data['area']}
অবস্থান: {$location}
পূর্ববর্তী ফসল: {$previousCrop}
অতিরিক্ত নোট: {$notes}
EOT;

        try {
            // ✅ Send request to Gemini API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-goog-api-key' => env('GEMINI_API_KEY'),
            ])
            ->timeout(60)
            ->post(env('GEMINI_API_URL'), [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API failed', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return response()->json(['error' => 'AI সার্ভিস ব্যর্থ হয়েছে। পরে চেষ্টা করুন।'], 500);
            }

            $body = $response->json();
            $suggestions = $body['candidates'][0]['content']['parts'][0]['text'] ?? 'কোনো পরামর্শ পাওয়া যায়নি।';

            return response()->json(['suggestions' => $suggestions]);

        } catch (\Throwable $e) {
            Log::error('Gemini request exception: ' . $e->getMessage());
            return response()->json(['error' => 'সার্ভার সমস্যা হয়েছে। পরে চেষ্টা করুন।'], 500);
        }
    }
}
