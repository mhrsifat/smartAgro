<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AnalyzeCropImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $imagePaths;

    public function __construct(array $imagePaths)
    {
        $this->imagePaths = $imagePaths;
    }

    public function handle(): void
    {
        $cacheKey = 'diagnosis_all';

        try {
            // set structured processing state
            Cache::put($cacheKey, ['status' => 'processing', 'diagnosis' => null], now()->addMinutes(30));
            Log::info('AnalyzeCropImages started', ['images' => $this->imagePaths]);

            // build request parts: instruction + images
            $parts = [
                [
                    'text' => 'You are an experienced plant pathologist and agronomist with practical field experience diagnosing foliar diseases in vegetable and cash crops. Carefully analyze all provided images together and produce **one combined report in Bengali (বাংলা)**. Output must be **Markdown only** and follow this professional structure exactly.

- Output language: বাংলা (Bangla).
- Tone: professional, concise, actionable — suitable for agronomists and extension officers.
- Sections (use H2 for main sections, H3 for sub-sections). Separate sections with horizontal rules (---).

Required sections and format:
1. ## Executive summary  
   - 2–3 short sentences summarizing the overall findings and confidence.

2. ## Per-image observations  
   - For each image (label as **Image 1: \<filename\>**, **Image 2: \<filename\>**, ...):  
     - ### Observed symptoms  
       - Bullet list of visible signs (short phrases).  
     - ### Immediate impression (differential)  
       - Bullet list of 2–3 possible diagnoses with short rationale and **confidence** (High / Medium / Low).

3. ## Combined diagnosis (consolidated)  
   - List the most likely diagnoses (H2 or H3 as appropriate). For each diagnosis include:
     - Short description (1–2 lines).
     - Confidence level (High / Medium / Low).
     - Clear rationale citing which images / symptoms support this.

4. ## Immediate actions (urgent steps) — short, practical steps with emojis (✔️, ❌, 🌱)  
   - For each action include:
     - What to do now (e.g., remove infected leaves, isolate plant).  
     - If chemical control is recommended: specify **active ingredient** (English term) and **recommended dose** (e.g., g or ml per liter), spray interval, and safety note. Provide one or two example product names common in Bangladesh if available.

5. ## Treatment options (detailed)  
   - Non-chemical measures (cultural, sanitation).  
   - Chemical measures: active ingredient → dosage → timing → number of sprays.  
   - Note resistance-avoidance tips (rotate actives).

6. ## Prevention & good practices  
   - Planting, irrigation, spacing, seed selection, nutrient notes.

7. ## Sampling & when to consult an expert  
   - How to collect and store a sample, which specialist to contact, and what information to provide.

8. ## Short reference / next steps  
   - 1–2 suggested resources or short next-step checklist.

Formatting rules:
- Use H2 for main sections, H3 for sub-sections.  
- Use bullet points and short lines (keep each bullet ≤ 2 lines).  
- Use emojis where helpful (✔️, ❌, 🌱, 🔬).  
- Add horizontal rules (`---`) between major sections.  
- Keep the whole report concise (target ~300–800 words), but cover all sections.  
- If uncertain, state the top 3 differential diagnoses with confidence values.  
- **Do not output HTML** — only Markdown.  
- If any image is unreadable or missing, note it under Per-image observations (e.g., "Image 3: unreadable / low resolution").

Important: Include Bangla translations in parentheses immediately after any English technical term you use (for example: "active ingredient (সক্রিয় উপাদান)"), to help local extension workers.

End the report with a one-line actionable next step: e.g., **"Next step:** contact the nearest agricultural officer and send 1 fresh symptomatic leaf sample."**
'
                ]
            ];

            foreach ($this->imagePaths as $imagePath) {
                $imageRealPath = storage_path('app/public/' . $imagePath);
                if (file_exists($imageRealPath)) {
                    $parts[] = [
                        'inline_data' => [
                            'mime_type' => mime_content_type($imageRealPath) ?: 'image/jpeg',
                            'data' => base64_encode(file_get_contents($imageRealPath)),
                        ]
                    ];
                } else {
                    Log::warning('AnalyzeCropImages: image file not found', ['path' => $imageRealPath]);
                }
            }

            // Call the external API
            $response = Http::timeout(300)
                ->post(env('GEMINI_API_URL') . '?key=' . env('GEMINI_API_KEY'), [
                    'contents' => [['parts' => $parts]]
                ]);

            if (! $response->successful()) {
                $errMsg = 'API request failed: ' . $response->status();
                Cache::put($cacheKey, ['status' => 'failed', 'diagnosis' => 'Error: ' . $errMsg], now()->addMinutes(30));
                Log::error('AnalyzeCropImages: API request failed', ['status' => $response->status(), 'body' => $response->body()]);
                return;
            }

            $result = $response->json();

            // Defensive extraction of text parts
            $partsFromApi = $result['candidates'][0]['content']['parts'] ?? [];
            $diagnosisText = collect($partsFromApi)->pluck('text')->filter()->implode("\n");

            if (empty($diagnosisText)) {
                $diagnosisText = 'No result from AI';
            }

            $converter = new CommonMarkConverter();
            $html = (string) $converter->convert($diagnosisText);

            // Save structured cache: status + html
            Cache::put($cacheKey, ['status' => 'completed', 'diagnosis' => $html], now()->addMinutes(60));
            Log::info('AnalyzeCropImages: Diagnosis cached', ['key' => $cacheKey, 'length' => strlen($html)]);

            // Optional: write debug HTML for manual inspection
            try {
                Storage::disk('public')->put('results/diagnosis_all.html', $html);
            } catch (\Throwable $e) {
                Log::warning('AnalyzeCropImages: failed to write HTML file', ['error' => $e->getMessage()]);
            }
        } catch (\Throwable $e) {
            $err = 'Error: ' . $e->getMessage();
            Cache::put($cacheKey, ['status' => 'failed', 'diagnosis' => $err], now()->addMinutes(30));
            Log::error('AnalyzeCropImages failed', ['exception' => $e->getMessage()]);
        }
    }
}
