<?php

namespace Modules\Research\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $researches = \Modules\Research\Models\Research::latest()->paginate(10);
    return view('research::index', compact('researches'));
}

public function show($id)
{
    $research = \Modules\Research\Models\Research::findOrFail($id);
    return view('research::show', compact('research'));
}
}
