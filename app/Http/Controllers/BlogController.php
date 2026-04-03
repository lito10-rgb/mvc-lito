<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::where('estado', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('blog.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)
            ->where('estado', 1)
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
