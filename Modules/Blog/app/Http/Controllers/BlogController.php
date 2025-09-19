<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('blog::index');
    }

    
    public function show(Blog $blog)
{
    return view('blog::show', compact('blog'));
}
    
}
