<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Jobs\AnalyzeCropImages;

class DiseaseController extends Controller
{
    public function analyze(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image|max:4096',
        ]);

        $uploadedImages = [];
        $paths = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('uploads', 'public');
            $uploadedImages[] = asset('storage/' . $path);
            $paths[] = $path;
        }

        // clear previous combined result so frontend won't read stale state
        Cache::forget('diagnosis_all');

        // DEBUG OPTION (temporary): run synchronously in the request
        // AnalyzeCropImages::dispatchSync($paths);

        // PRODUCTION: dispatch to queue (requires queue worker)
        AnalyzeCropImages::dispatch($paths);

        return response()->json(['uploadedImages' => $uploadedImages]);
    }

    public function diseasePage()
    {
        return view('diseasePage');
    }

    public function checkDiagnosis()
    {
        $value = Cache::get('diagnosis_all', null);

        // If no cache entry -> still processing
        if ($value === null) {
            return response()->json(['status' => 'processing', 'diagnosis' => null]);
        }

        // If cached value is structured (array), return as-is (safe)
        if (is_array($value)) {
            return response()->json([
                'status' => $value['status'] ?? 'processing',
                'diagnosis' => $value['diagnosis'] ?? null
            ]);
        }

        // Fallback: handle old-style string values
        if (is_string($value)) {
            if ($value === 'processing') {
                return response()->json(['status' => 'processing', 'diagnosis' => null]);
            }
            if (str_starts_with($value, 'Error:')) {
                return response()->json(['status' => 'failed', 'diagnosis' => $value]);
            }
            // assume it's raw HTML
            return response()->json(['status' => 'completed', 'diagnosis' => $value]);
        }

        // Default fallback
        return response()->json(['status' => 'processing', 'diagnosis' => null]);
    }
}
