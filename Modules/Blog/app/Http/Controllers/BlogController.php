<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Blog\Models\Blog;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Blog::with('author');

        if ($request->search) {
            $query->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
        }

        // If Axios/Fetch request (expects JSON)
        if ($request->ajax()) {
            $blogs = $query->latest()->paginate(9);
            return response()->json($blogs);
        }

        $blogs = $query->latest()->paginate(9);
        return view('blog::blogs', compact('blogs'));
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $blog = Blog::with('author')->where('slug', $slug)->firstOrFail();
        return view('blog::blogs-single', compact('blog'));
    }
}