<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(10);
        return view('home', compact('posts'));
    }

    public function view($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return view('view', compact('post'));
    }
}
