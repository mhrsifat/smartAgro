<?php

namespace Modules\Research\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $researches = \Modules\Research\Models\Research::latest()->paginate(10);
    return view('research::admin.index', compact('researches'));
}

public function create()
{
    return view('research::admin.create');
}

public function show($id)
{
    $research = \Modules\Research\Models\Research::findOrFail($id);
    return view('research::admin.show', compact('research'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'slug' => 'required|string|unique:researches,slug',
        'image' => 'nullable|image',
        'authors' => 'nullable|array',
        'status' => 'required|in:draft,under_review,published',
        'is_featured' => 'nullable|boolean',
        'download_url' => 'nullable|url',
        'user_id' => 'nullable|exists:users,id',
    ]);

    $data['authors'] = $data['authors'] ? json_encode($data['authors']) : null;

    \Modules\Research\Models\Research::create($data);

    return redirect()->route('admin.researches.index')->with('success', 'Research created successfully.');
}

public function edit($id)
{
    $research = \Modules\Research\Models\Research::findOrFail($id);
    return view('research::admin.edit', compact('research'));
}

public function update(Request $request, $id)
{
    $research = \Modules\Research\Models\Research::findOrFail($id);

    $data = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'slug' => "required|string|unique:researches,slug,{$id}",
        'image' => 'nullable|image',
        'authors' => 'nullable|array',
        'status' => 'required|in:draft,under_review,published',
        'is_featured' => 'nullable|boolean',
        'download_url' => 'nullable|url',
        'user_id' => 'nullable|exists:users,id',
    ]);

    $data['authors'] = $data['authors'] ? json_encode($data['authors']) : null;

    $research->update($data);

    return redirect()->route('admin.researches.index')->with('success', 'Research updated successfully.');
}

public function destroy($id)
{
    $research = \Modules\Research\Models\Research::findOrFail($id);
    $research->delete();

    return redirect()->route('admin.researches.index')->with('success', 'Research deleted successfully.');
}
}
