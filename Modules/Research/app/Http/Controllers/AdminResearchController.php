<?php

namespace Modules\Research\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Research\Models\Research;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminResearchController extends Controller
{
    public function index()
    {
        $researches = Research::latest()->paginate(10);
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
        'title'        => 'required|string|max:255',
        'description'  => 'required|string',
        'slug'         => 'required|string|unique:researches,slug',
        'image'        => 'nullable|image',
        'authors'      => 'nullable|string',
        'status'       => 'required|in:draft,under_review,published',
        'is_featured'  => 'nullable|boolean',
        'paper'        => 'nullable|file|mimes:pdf,doc,docx',
    ]);

    // Featured image
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('uploads/researches/images', 'public');
        $data['image'] = 'storage/' . $path;
    } else {
        $data['image'] = 'storage/uploads/researches/images/default.jpg'; 
        // Make sure this file exists or set null
    }

    // Research paper
    if ($request->hasFile('paper')) {
        $path = $request->file('paper')->store('uploads/researches/papers', 'public');
        $data['paper'] = 'storage/' . $path;
    }

    $data['is_featured'] = $request->boolean('is_featured');

    // Assign logged-in user
    $data['user_id'] = auth()->id();

    Research::create($data);

    return redirect()->route('admin.researches.index')
        ->with('success', 'Research created successfully.');
}

    public function show(Research $research)
    {
        return view('research::admin.show', compact('research'));
    }

    public function edit(Research $research)
    {
        $users = User::pluck('name', 'id');
        return view('research::admin.edit', compact('research', 'users'));
    }

    public function update(Request $request, Research $research)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'required|string',
            'slug'         => "required|string|unique:researches,slug,{$research->id}",
            'image'        => 'nullable|image',
            'authors'      => 'nullable|string',
            'status'       => 'required|in:draft,under_review,published',
            'is_featured'  => 'nullable|boolean',
            'download_url' => 'nullable|url',
            'paper'        => 'nullable|file|mimes:pdf,doc,docx',
        ]);

        // Replace image
        if ($request->hasFile('image')) {
            if ($research->image && str_contains($research->image, 'storage/')) {
                $oldPath = str_replace('storage/', '', $research->image);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('image')->store('uploads/researches/images', 'public');
            $data['image'] = 'storage/' . $path;
        }

        // Replace paper
        if ($request->hasFile('paper')) {
            if ($research->paper && str_contains($research->paper, 'storage/')) {
                $oldPath = str_replace('storage/', '', $research->paper);
                Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('paper')->store('uploads/researches/papers', 'public');
            $data['paper'] = 'storage/' . $path;
        }

        $data['is_featured'] = $request->boolean('is_featured');

        $research->update($data);

        return redirect()->route('admin.researches.index')
            ->with('success', 'Research updated successfully.');
    }

    public function destroy(Research $research)
    {
        if ($research->image && str_contains($research->image, 'storage/')) {
            $oldPath = str_replace('storage/', '', $research->image);
            Storage::disk('public')->delete($oldPath);
        }

        if ($research->paper && str_contains($research->paper, 'storage/')) {
            $oldPath = str_replace('storage/', '', $research->paper);
            Storage::disk('public')->delete($oldPath);
        }

        $research->delete();

        return redirect()->route('admin.researches.index')
            ->with('success', 'Research deleted successfully.');
    }
}
