<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Jobs\AnalyzeCropImages;
use App\Models\Diagnosis;

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
        if ($diagnosis->user_id && $request->user()->id !== $diagnosis->user_id) {
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
            'file_path' => $diagnosis->file_path ? Storage::disk('public')->url($diagnosis->file_path) : null,
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

        $userKey = auth()->check() ? 'user_' . auth()->id() : 'guest_' . session()->getId();
        Cache::forget('diagnosis_all_' . $userKey);

        AnalyzeCropImages::dispatch($paths, $userKey);

        return response()->json(['uploadedImages' => $uploadedImages, 'userKey' => $userKey]);
    }

    public function checkDiagnosis(Request $request)
    {
        $userKey = auth()->check() ? 'user_' . auth()->id() : 'guest_' . session()->getId();
        $value = Cache::get('diagnosis_all_' . $userKey, null);

        if ($value === null) {
            return response()->json(['status' => 'processing', 'diagnosis' => null]);
        }

        return response()->json([
            'status' => $value['status'] ?? 'processing',
            'diagnosis' => $value['diagnosis'] ?? null
        ]);
    }
}

