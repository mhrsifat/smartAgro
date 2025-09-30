<?php

namespace Modules\SuccessStories\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SuccessStories\Models\SuccessStory;

class SuccessStoriesController extends Controller
{
    public function index(Request $request)
    {
        $query = SuccessStory::query()->where('status', true);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('summary', 'like', '%' . $request->search . '%');
        }

        $successstories = $query->latest()->paginate(9);

        if ($request->ajax()) {
            return response()->json($successstories);
        }

        return view('successstories::index', compact('successstories'));
    }

    public function show(SuccessStory $successstory)
{
    return view('successstories::show', compact('successstory'));
}
}