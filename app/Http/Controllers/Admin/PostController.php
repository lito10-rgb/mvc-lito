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
use App\Models\Rubro;
use App\Models\Role;
use App\Mail\PostShared;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
class PostController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('id', 'desc')->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
     $users = User::with('profile', 'rubros', 'roles')->orderBy('nombre')->get();
     $platforms = Platform::orderBy('nombre')->get();
     $rubros = Rubro::orderBy('nombre')->get();
     $roles = Role::orderBy('nombre')->get();

    return view('admin.posts.create', compact('users', 'platforms', 'rubros', 'roles'));
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
    $request->validate([
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ]);

    $data = [
        'titulo' => $request->titulo,
        'slug'   => Str::slug($request->titulo),
        'cuerpo' => $request->contenido,
        'estado' => (int) $request->estado ?: 1,
    ];

    if ($request->hasFile('imagen')) {
        $data['imagen'] = $request->file('imagen')->store('imagenes/posts', 'public');
    }

    $post = Post::create($data);

    if ($request->has('users')) {
        $post->users()->sync($request->users);
    }
    if ($request->has('platforms')) {
        $post->platforms()->sync($request->platforms);
    }

    if ($request->has('users')) {
        $selectedUsers = User::whereIn('id', $request->users)->get();
        foreach ($selectedUsers as $user) {
            Mail::to($user->email)->send(new PostShared($post, $user));
        }
    }

    return redirect()->route('admin.posts.index')->with('success', 'Post creado correctamente.');
}

    public function show($slug) {
    $post = Post::where('slug', $slug)
                ->where('estado', 1)
                ->firstOrFail();

    return view('blog.show', compact('post'));
    }
    public function edit($id)
    {
        $post = Post::with('users', 'platforms')->findOrFail($id);
        $users = User::with('profile', 'rubros', 'roles')->orderBy('nombre')->get();
        $platforms = Platform::orderBy('nombre')->get();
        $rubros = Rubro::orderBy('nombre')->get();
        $roles = Role::orderBy('nombre')->get();

        return view('admin.posts.edit', compact('post', 'users', 'platforms', 'rubros', 'roles'));
    }
    public function update(Request $request, $id)
{
    $post = Post::findOrFail($id);

    $post->titulo = $request->titulo;
    $post->cuerpo = $request->contenido;
    $post->slug   = Str::slug($request->titulo);
    $post->estado = (int) $request->estado;

    if ($request->hasFile('imagen')) {
        $post->imagen = $request->file('imagen')->store('imagenes/posts', 'public');
    }

    $post->save();

    if ($request->has('users')) {
        $post->users()->sync($request->users);
    }
    if ($request->has('platforms')) {
        $post->platforms()->sync($request->platforms);
    }

    return redirect()
        ->route('admin.posts.index')
        ->with('success', 'Post actualizado correctamente');
}

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post eliminado.');
    }

}
