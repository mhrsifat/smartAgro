<?php

namespace App\Http\Controllers;

use Modules\Research\Models\Research;
use Modules\Blog\Models\Blog;
use Modules\Donation\Models\Donation;
use Modules\SuccessStories\Models\SuccessStory;

class HomeController extends Controller
{
    public function home()
    {
        // Fetch latest 3 items from each model using Eloquent ORM
        $researches    = Research::latest()->take(3)->get();
        $blogs         = Blog::latest()->take(3)->get();
        $donations     = Donation::latest()->take(3)->get();
        $successStories= SuccessStory::latest()->take(3)->get();

        // Return the 'home' view with the data
        return view('home', compact('researches', 'blogs', 'donations', 'successStories'));
    }
}