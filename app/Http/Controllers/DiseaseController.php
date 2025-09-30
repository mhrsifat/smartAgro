<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Jobs\AnalyzeCropImages;
use App\Models\Diagnosis;
use Illuminate\Support\Facades\Auth;

class DiseaseController extends Controller
{

    public function diseasePage()
    {
        return view('diseasePage');
    }

    public function show(Request $request, $id)
    {
        $diagnosis = Diagnosis::findOrFail($id);

        // authorize: if record has user_id, ensure current user owns it
        if ($diagnosis->user_id && $request->user()->id !== (int) $diagnosis->user_id) {
            abort(403);
        }

        $html = null;
        if ($diagnosis->file_path && Storage::disk('public')->exists($diagnosis->file_path)) {
            $html = Storage::disk('public')->get($diagnosis->file_path);
        }

        return response()->json([
            'id' => $diagnosis->id,
            'status' => $diagnosis->status,
            'excerpt' => $diagnosis->excerpt,
            'html' => $html,
            'url' => $diagnosis->file_path ? config('app.url') . '/storage/' . $diagnosis->file_path : null,
            'file_path' => $diagnosis->file_path ? asset('storage/' . $diagnosis->file_path) : null,
        ]);
    }

    public function resultShow(Request $request, $id)
    {
        $diagnosis = Diagnosis::findOrFail($id);

        // authorize: if record has user_id, ensure current user owns it
        if ($diagnosis->user_id) {
            if (!auth()->check()) {
                abort(403, 'Please login to view this diagnosis');
            }

            if (auth()->id() !== (int) $diagnosis->user_id) {
                abort(403, 'You can only view your own diagnoses');
            }
        }

        $html = null;
        if ($diagnosis->file_path && Storage::disk('public')->exists($diagnosis->file_path)) {
            $html = Storage::disk('public')->get($diagnosis->file_path);
        }

        return view('diagnosisResult', [
            'diagnosis' => $diagnosis,
            'html' => $html,
        ]);
    }

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

        $userId = auth()->check() ? auth()->id() : null;
        $userKey = auth()->check() ? 'user_' . auth()->id() . '_' : 'guest_' . session()->getId();
        // When constructing or before caching
      $userKey = $userKey . now()->timestamp . '_' . uniqid();

        Cache::forget('diagnosis_all_' . $userKey);

        AnalyzeCropImages::dispatch($paths, $userKey, $userId);

        return response()->json(['uploadedImages' => $uploadedImages, 'userKey' => $userKey]);
    }

    public function checkDiagnosis(Request $request, $userKey)
    {
        $userKey = $userKey;
        
        $value = Cache::get('diagnosis_all_' . $userKey, null);

        if ($value === null) {
            return response()->json(['status' => 'processing', 'diagnosis' => null]);
        }

        return response()->json([
            'status' => $value['status'] ?? 'processing',
            'diagnosis' => $value['diagnosis'] ?? null
        ]);
    }
    
    public function poll($userKey)
{
    $diagnosis = Diagnosis::where('user_key', $userKey)->first();

    if (!$diagnosis) {
        return response()->json([
            'status' => 'processing',
            'html'   => '<div class="text-gray-500">⏳ Still processing...</div>',
        ]);
    }
    
    $html = null;
        if ($diagnosis->file_path && Storage::disk('public')->exists($diagnosis->file_path)) {
            $html = Storage::disk('public')->get($diagnosis->file_path);
        }

        return response()->json([
            'id' => $diagnosis->id,
            'status' => $diagnosis->status,
            'excerpt' => $diagnosis->excerpt,
            'html' => $html,
            'url' => $diagnosis->file_path ? config('app.url') . '/storage/' . $diagnosis->file_path : null,
            'file_path' => $diagnosis->file_path ? asset('storage/' . $diagnosis->file_path) : null,
        ]);

}
}
