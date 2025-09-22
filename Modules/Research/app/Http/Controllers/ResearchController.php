<?php

namespace Modules\Research\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = \Modules\Research\Models\Research::query();

    if ($request->search) {
        $query->where('title', 'like', "%{$request->search}%")
              ->orWhere('description', 'like', "%{$request->search}%");
    }

    // If Axios request (expects JSON)
    if ($request->ajax()) {
        $researches = $query->latest()->paginate(9);
        return response()->json($researches);
    }

    $researches = $query->latest()->paginate(9);
    return view('research::index', compact('researches'));
}

public function show($slug)
{
    $research = \Modules\Research\Models\Research::where('slug', $slug)->firstOrFail();
    return view('research::show', compact('research'));
}
}
