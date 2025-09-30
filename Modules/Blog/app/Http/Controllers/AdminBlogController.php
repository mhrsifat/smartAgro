<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Blog\Models\Blog;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class AdminBlogController extends Controller
{
    public function index(Request $request)
{
    $query = Blog::query()->with('author'); // eager load author to prevent N+1 query

    if ($search = $request->input('q')) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhereHas('author', function ($authorQuery) use ($search) {
                  $authorQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    $blogs = $query->latest()->paginate(10)->withQueryString();

    return view('blog::index', compact('blogs'));
}

    public function create()
    {
        $users = User::pluck('name', 'id');
        return view('blog::create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog)
    {
        $users = User::pluck('name', 'id');
        return view('blog::edit', compact('blog', 'users'));
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $this->validateData($request, $blog->id);

        if ($request->hasFile('image')) {
            $this->deleteImage($blog->image);
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog)
    {
        $this->deleteImage($blog->image);
        $blog->delete();

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog deleted successfully.');
    }

    public function show(Blog $blog)
    {
        return view('blog::show', compact('blog'));
    }

    /**
     * Validate blog data
     */
    protected function validateData(Request $request, $blogId = null)
    {
        $uniqueSlug = 'unique:blogs,slug';
        if ($blogId) {
            $uniqueSlug .= ',' . $blogId;
        }

        return $request->validate([
            'title'     => 'required|string|max:255',
            'content'   => 'required|string',
            'excerpt'   => 'nullable|string|max:500',
            'slug'      => "required|string|$uniqueSlug",
            'image'     => 'nullable|image|max:2048',
            'author_id' => 'nullable|exists:users,id',
            'status'    => 'required|in:draft,published',
        ]);
    }

    /**
     * Upload image to storage
     */
    protected function uploadImage($image)
    {
        return $image->store('blog_images', 'public');
    }

    /**
     * Delete image from storage
     */
    protected function deleteImage($image)
    {
        if ($image) {
            Storage::disk('public')->delete($image);
        }
    }
}