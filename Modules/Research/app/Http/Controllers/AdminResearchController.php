<?php

namespace Modules\Research\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */public function index()
{
    $researches = \Modules\Research\Models\Research::latest()->paginate(10);
    return view('research::admin.index', compact('researches'));
}

public function create()
{
    $users = User::pluck('name', 'id');
    return view('research::admin.create', compact('users'));
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

    // handle image upload
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('uploads/researches', 'public');
        $data['image'] = 'storage/' . $path;
    }

    $data['authors'] = $data['authors'] ? json_encode($data['authors']) : null;
    $data['is_featured'] = isset($data['is_featured']) ? (bool)$data['is_featured'] : false;

    \Modules\Research\Models\Research::create($data);

    return redirect()->route('admin.researches.index')->with('success', 'Research created successfully.');
}
    

public function show($id)
{
    $research = \Modules\Research\Models\Research::findOrFail($id);
    return view('research::admin.show', compact('research'));
}


public function edit($id)
{
    $research = \Modules\Research\Models\Research::findOrFail($id);
    $users = User::pluck('name', 'id');
    return view('research::admin.edit', compact('research', 'users'));
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

    if ($request->hasFile('image')) {
        // optional: delete old image if you stored path in storage
        if ($research->image && str_contains($research->image, 'storage/')) {
            $oldPath = str_replace('storage/', '', $research->image);
            Storage::disk('public')->delete($oldPath);
        }
        $path = $request->file('image')->store('uploads/researches', 'public');
        $data['image'] = 'storage/' . $path;
    }

    $data['authors'] = $data['authors'] ? json_encode($data['authors']) : null;
    $data['is_featured'] = isset($data['is_featured']) ? (bool)$data['is_featured'] : false;

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

