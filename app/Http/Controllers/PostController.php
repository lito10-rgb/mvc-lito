<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class PostController extends Controller
{
    public function create()
    {
        return view('admin.posts.create', [
            'users'     => User::orderBy('nombre')->get(),
            'platforms' => Platform::where('estado', 1)->get(),
        ]);
    }

    // public function store(Request $request)
    // {
    //     $post = Post::create(
    //         $request->only('titulo', 'cuerpo')
    //     );

    //     if ($request->users) {
    //         $post->users()->sync($request->users);
    //     }

    //     if ($request->platforms) {
    //         $post->platforms()->sync($request->platforms);
    //     }

    //     return redirect()->back()->with('ok', 'Post creado');
    // }
    public function store(Request $request)
{
    $request->validate([
        'titulo' => 'required|string|max:255',
        'cuerpo' => 'required|string',
    ]);

    $post = Post::create([
        'titulo' => $request->titulo,
        'cuerpo' => $request->cuerpo,
        'slug'   => Str::slug($request->titulo),
        'estado' => 1,
    ]);

    if ($request->users) {
        $post->users()->sync($request->users);
    }

    if ($request->platforms) {
        $post->platforms()->sync($request->platforms);
    }

    return redirect()->back()->with('ok', 'Post creado');
}
}
