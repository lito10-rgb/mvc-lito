<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerSlide;
use App\Models\Negocio;
use Illuminate\Http\Request;

class BannerSlideController extends Controller
{
    public function index(Negocio $negocio)
    {
        $slides = $negocio->bannerSlides()->with('categoria')->get();
        return view('admin.negocios.slides.index', compact('negocio', 'slides'));
    }

    public function create(Negocio $negocio)
    {
        $categorias = \App\Models\Categoria::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocio->id))->get();
        return view('admin.negocios.slides.form', compact('negocio', 'categorias'));
    }

    public function store(Request $request, Negocio $negocio)
    {
        $data = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'subtitulo' => 'nullable|string',
            'boton_texto' => 'nullable|string|max:255',
            'boton_url' => 'nullable|string|max:255',
            'orden' => 'nullable|integer|min:0',
            'color_texto' => 'nullable|string|max:20',
            'color_boton_fondo' => 'nullable|string|max:20',
            'color_boton_texto' => 'nullable|string|max:20',
            'posicion' => 'nullable|string|in:left,center,right',
            'categoria_id' => 'nullable|integer|exists:categorias,id',
        ]);

        $data['negocio_id'] = $negocio->id;

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('negocios/slides', 'public');
        }

        BannerSlide::create($data);
        return redirect()->route('admin.negocios.slides.index', $negocio)
            ->with('success', 'Slide creado correctamente.');
    }

    public function edit(Negocio $negocio, BannerSlide $slide)
    {
        $categorias = \App\Models\Categoria::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocio->id))->get();
        return view('admin.negocios.slides.form', compact('negocio', 'slide', 'categorias'));
    }

    public function update(Request $request, Negocio $negocio, BannerSlide $slide)
    {
        $data = $request->validate([
            'titulo' => 'nullable|string|max:255',
            'subtitulo' => 'nullable|string',
            'boton_texto' => 'nullable|string|max:255',
            'boton_url' => 'nullable|string|max:255',
            'orden' => 'nullable|integer|min:0',
            'color_texto' => 'nullable|string|max:20',
            'color_boton_fondo' => 'nullable|string|max:20',
            'color_boton_texto' => 'nullable|string|max:20',
            'posicion' => 'nullable|string|in:left,center,right',
            'categoria_id' => 'nullable|integer|exists:categorias,id',
        ]);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('negocios/slides', 'public');
        }

        $slide->update($data);
        return redirect()->route('admin.negocios.slides.index', $negocio)
            ->with('success', 'Slide actualizado correctamente.');
    }

    public function destroy(Negocio $negocio, BannerSlide $slide)
    {
        $slide->delete();
        return redirect()->route('admin.negocios.slides.index', $negocio)
            ->with('success', 'Slide eliminado.');
    }
}
