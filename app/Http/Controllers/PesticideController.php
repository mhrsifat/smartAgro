<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PesticideController extends Controller
{
    public function showForm()
    {
        return view('pesticideSuggestor');
    }

    public function recommendPesticide(Request $request)
    {
        $data = $request->validate([
            'crop' => 'required|string|max:100',
            'pest_type' => 'required|string|max:150',
            'location' => 'nullable|string|max:150',
            'notes' => 'nullable|string|max:500',
        ]);

        $location = $data['location'] ?? 'উল্লেখ নেই';
        $notes = $data['notes'] ?? 'কোনো নোট নেই';

        $prompt = <<<EOT
আপনি একজন কৃষি বিশেষজ্ঞ (Agricultural Advisor)। শুরু করুন "আসসালামু আলাইকুম" দিয়ে। নিচের তথ্য অনুযায়ী এই ফসলের জন্য সর্বোত্তম কীটনাশক বা প্রতিরোধমূলক পদ্ধতি বাংলায় পরামর্শ দিন। 
ফলে সম্ভাব্য ফলন প্রভাব ও সঠিক ব্যবহার নিয়মও সংক্ষেপে দিন।

ফসলের নাম: {$data['crop']}
পোকা/রোগের ধরন: {$data['pest_type']}
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
