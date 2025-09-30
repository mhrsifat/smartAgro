<?php

namespace App\Jobs;

use App\Events\DiagnosisUpdated;
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
use App\Notifications\DiagnosisReady;
use App\Models\Diagnosis;

class AnalyzeCropImages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $imagePaths;
    protected ?string $userKey;
    protected ?int $userId;

    public function __construct(array $imagePaths, ?string $userKey = null, ?int $userId = null)
    {
        $this->imagePaths = $imagePaths;
        $this->userKey = $userKey;
        $this->userId = $userId;
    }

    public function handle(): void
    {
        $cacheKey = 'diagnosis_all_' . ($this->userKey ?? 'guest' . session()->getId());
        $diagnosis = null;

        try {
            // Set initial processing state in cache
            Cache::put($cacheKey, ['status' => 'processing', 'diagnosis' => null], now()->addMinutes(30));
            Log::info('AnalyzeCropImages started', ['images' => $this->imagePaths]);

            // Prepare API request
            $parts = [[
                'text' => 'You are an experienced plant pathologist and agronomist with practical field experience diagnosing foliar diseases in vegetable and cash crops. Carefully analyze all provided images together and produce **one combined report in Bengali (বাংলা)**. Output must be **Markdown only** and follow this professional structure exactly.

- Output language: বাংলা (Bangla).
- Tone: professional, concise, actionable — suitable for agronomists and extension officers.
- Sections (use H2 for main sections, H3 for sub-sections). Separate sections with horizontal rules (---).

Required sections and format:
1. ## Executive summary  
   - 2–3 short sentences summarizing the overall findings and confidence.

2. ## Per-image observations  
   - For each image (label as **Image 1: \<imageinfo\>**, **Image 2: \<imageinfo\>**, ...):  
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

Important: Include Bangla translations in parentheses immediately after any English technical term you use (for example: "active ingredient (সক্রিয় উপাদান)"), to help local extension workers.

End the report with a one-line actionable next step: e.g., **"Next step:** contact the nearest agricultural officer and send 1 fresh symptomatic leaf sample."**'
            ]];

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

            // Call external API
            $response = Http::timeout(300)
                ->post(env('GEMINI_API_URL') . '?key=' . env('GEMINI_API_KEY'), [
                    'contents' => [['parts' => $parts]]
                ]);

            if (! $response->successful()) {
                $errMsg = 'API request failed: ' . $response->status();
                $this->handleResult('failed', null, "<div class='text-red-500'>{$errMsg}</div>");
                Log::error('AnalyzeCropImages: API request failed', ['status' => $response->status(), 'body' => $response->body()]);
                return;
            }

            // Extract result text
            $result = $response->json();
            $partsFromApi = $result['candidates'][0]['content']['parts'] ?? [];
            $diagnosisText = collect($partsFromApi)->pluck('text')->filter()->implode("\n") ?: 'No result from AI';
            $converter = new CommonMarkConverter();
            $html = (string) $converter->convert($diagnosisText);

            // Save result file
            $idPart = $this->userKey ? preg_replace('/[^A-Za-z0-9_\-]/', '_', $this->userKey) : now()->format('YmdHis');
            $filename = "results/diagnosis_{$idPart}_" . uniqid() . ".html";
            Storage::disk('public')->put($filename, $html);

            // Save DB record
            $diagnosis = Diagnosis::create([
                'user_id' => $this->userId,
                'user_key' => $this->userKey,
                'status' => 'completed',
                'file_path' => $filename,
                'excerpt' => \Illuminate\Support\Str::limit(strip_tags($diagnosisText), 300),
                'html_length' => strlen($html),
            ]);

            $this->handleResult('completed', $diagnosis->id, "Diagnosis Completed.");

            Log::info('AnalyzeCropImages completed successfully', ['diagnosis_id' => $diagnosis->id]);

        } catch (\Throwable $e) {
            $err = 'Error: ' . $e->getMessage();
            $this->handleResult('failed', $diagnosis?->id, "<div class='text-red-500'>{$err}</div>");
            Log::error('AnalyzeCropImages failed', ['exception' => $e->getMessage(), 'userKey' => $this->userKey]);
        }
    }

    /**
     * Handle broadcasting / cache for logged-in vs guest users
     */
    private function handleResult(string $status, ?int $diagnosisId, string $htmlOrMessage): void
    {
        $cacheKey = 'diagnosis_all_' . ($this->userKey ?? 'guest' . session()->getId());

        if ($this->userId) {
            // Logged-in user → broadcast + notify
            event(new DiagnosisUpdated($status, $this->userKey, $diagnosisId, strip_tags($htmlOrMessage)));

            if ($status === 'completed' && $diagnosisId) {
                $user = \App\Models\User::find($this->userId);
                if ($user) {
                    $user->notify(new DiagnosisReady($status, $diagnosisId, 'Diagnosis completed successfully!'));
                }
            }
        } else {
            // Guest → update cache only
            Cache::put($cacheKey, [
                'status' => $status,
                'diagnosis_id' => $diagnosisId,
                'html' => null,
            ], now()->addHours(1));
        }
    }
}