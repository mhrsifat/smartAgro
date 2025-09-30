<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FertilizerController extends Controller
{
    public function showForm()
    {
        return view('fertilizerSuggestor');
    }

    public function recommendFertilizer(Request $request)
    {
        $data = $request->validate([
            'crop' => 'required|string|max:100',
            'soil_type' => 'required|string|max:100',
            'area' => 'required|numeric|min:0.1',
            'location' => 'nullable|string|max:150',
            'notes' => 'nullable|string|max:500',
        ]);

        $location = $data['location'] ?? 'উল্লেখ নেই';
        $notes = $data['notes'] ?? 'কোনো নোট নেই';

        $prompt = <<<EOT
আসসালামু আলাইকুম। আপনি একজন কৃষি বিশেষজ্ঞ (Agricultural Advisor)। নিচের তথ্য অনুযায়ী এই ফসলের জন্য সর্বোত্তম সার ও পুষ্টি ব্যবস্থাপনা বাংলায় পরামর্শ দিন। 
প্রতিটি সার বা উপাদানের জন্য প্রয়োজনীয় পরিমাণ, প্রয়োগ সময় এবং ব্যবহার নিয়ম সংক্ষেপে দিন। ধাপে ধাপে সহজ ভাষায় লিখুন।

⚠️ নোট: এটি সাধারণ তথ্য, চূড়ান্ত সিদ্ধান্তের আগে স্থানীয় কৃষি অফিসারের সাথে যাচাই করুন।

ফসলের নাম: {$data['crop']}
মাটির ধরন: {$data['soil_type']}
জমির পরিমাণ (একর/হেক্টর): {$data['area']}
অবস্থান: {$location}
অতিরিক্ত নোট: {$notes}
EOT;

        try {
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
                    'body' => $response->body(),
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
