<?php

namespace App\Http\Controllers\Admin;
// use App\Http\Controllers\Controller;
// use App\Models\Post;
// use App\Models\User;
// use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Platform;
use Illuminate\Http\Request;
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
    //    $users = User::orderBy('nombre')->get();

    // return view('admin.posts.create', [
    //     'users' => $users
    // ]);
     $users = User::orderBy('nombre')->get();
    $platforms = Platform::orderBy('nombre')->get();

    return view('admin.posts.create', compact('users', 'platforms'));
    }
    // public function store(Request $request)
    // {
    //     Post::create([
    //         'titulo' => $request->titulo,
    //         'contenido' => $request->cuerpo,
    //         'estado' => 1,
    //     ]);

    //     return redirect()->route('admin.posts.index');
    // }
    public function store(Request $request)
{
    Post::create([
        'titulo' => $request->titulo,
        'slug'   => Str::slug($request->titulo), // 🔥 AQUÍ
        'cuerpo' => $request->contenido, // ✅ ahora sí
        'estado' => 1,
    ]);

    return redirect()->route('admin.posts.index');
}

    public function show($slug) {
    $post = Post::where('slug', $slug)
                ->where('estado', 'Activo')
                ->firstOrFail();

    return view('blog.show', compact('post'));
    }
    public function edit($id)
    {
        $post = Post::findOrFail($id);

        return view('admin.posts.edit', compact('post'));
    }
   public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);

    $post->titulo = $request->titulo;
    $post->cuerpo = $request->contenido;
    $post->estado = (int) $request->estado; // 👈 punto y coma

    $post->save();
// dd('guardado');
    return redirect()
        ->route('admin.posts.index')
        ->with('success', 'Post actualizado correctamente');
}

}
