<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Jobs\AnalyzeCropImages;

class DiseaseController extends Controller
{
  
   public function diseasePage()
    {
        return view('diseasePage');
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