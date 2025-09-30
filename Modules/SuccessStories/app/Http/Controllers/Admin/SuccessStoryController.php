<?php

namespace Modules\SuccessStories\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SuccessStories\Models\SuccessStory;
use Illuminate\Support\Facades\Storage;

class SuccessStoryController extends Controller
{
    public function index(Request $request)
    {
        $query = SuccessStory::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $stories = $query->latest()->paginate(10);

        return view('successstories::admin.index', compact('stories'));
    }

    public function create()
    {
        return view('successstories::admin.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'author' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('success_stories', 'public');
        }

        SuccessStory::create($data);

        return redirect()->route('admin.successstories.index')
                         ->with('success', 'Success Story created successfully.');
    }

    public function show(SuccessStory $successStory)
    {
        return view('successstories::admin.show', compact('successStory'));
    }

    public function edit(SuccessStory $successStory)
    {
        return view('successstories::admin.edit', compact('successStory'));
    }

    public function update(Request $request, SuccessStory $successStory)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'author' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($successStory->image) {
                Storage::disk('public')->delete($successStory->image);
            }
            $data['image'] = $request->file('image')->store('success_stories', 'public');
        }

        $successStory->update($data);

        return redirect()->route('admin.successstories.index')
                         ->with('success', 'Success Story updated successfully.');
    }

    public function destroy(SuccessStory $successStory)
    {
        if ($successStory->image) {
            Storage::disk('public')->delete($successStory->image);
        }
        
        $successStory->delete();

        return redirect()->route('admin.successstories.index')
                         ->with('success', 'Success Story deleted successfully.');
    }
}
