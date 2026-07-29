<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NegocioController extends Controller
{
    public function index()
    {
        $negocios = Negocio::all();
        return view('admin.negocios.index', compact('negocios'));
    }

    public function edit(Negocio $negocio)
    {
        return view('admin.negocios.edit', compact('negocio'));
    }

    public function update(Request $request, Negocio $negocio)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'empresa' => 'nullable|string|max:500',
            'dominio' => 'required|string|max:100',
            'logo_height' => 'nullable|integer|min:20|max:300',
            'color_primary' => 'nullable|string|max:20',
            'color_secondary' => 'nullable|string|max:20',
            'color_accent' => 'nullable|string|max:20',
            'color_accent_light' => 'nullable|string|max:20',
            'color_header_bg' => 'nullable|string|max:20',
            'color_footer_bg' => 'nullable|string|max:20',
            'color_nav_btn' => 'nullable|string|max:20',
            'color_nav_btn_texto' => 'nullable|string|max:20',
            'color_nav_texto' => 'nullable|string|max:20',
            'nav_tipo' => 'nullable|string|in:texto,boton',
            'footer_phone' => 'nullable|string|max:50',
            'footer_email' => 'nullable|string|max:100',
            'footer_whatsapp' => 'nullable|string|max:50',
            'footer_address' => 'nullable|string|max:255',
            'footer_facebook' => 'nullable|string|max:255',
            'footer_twitter' => 'nullable|string|max:255',
            'footer_instagram' => 'nullable|string|max:255',
            'footer_linkedin' => 'nullable|string|max:255',
            'footer_html' => 'nullable|string',
            'map_lat' => 'nullable|numeric|between:-90,90',
            'map_lng' => 'nullable|numeric|between:-180,180',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('negocios/logos', 'public');
        }
        if ($request->hasFile('banner_entrada')) {
            $data['banner_entrada'] = $request->file('banner_entrada')->store('negocios/banners', 'public');
        }
        if ($request->hasFile('banner_categoria')) {
            $data['banner_categoria'] = $request->file('banner_categoria')->store('negocios/banners', 'public');
        }
        if ($request->hasFile('banner_subcategoria')) {
            $data['banner_subcategoria'] = $request->file('banner_subcategoria')->store('negocios/banners', 'public');
        }
        if ($request->hasFile('map_photo')) {
            $data['map_photo'] = $request->file('map_photo')->store('negocios/mapa', 'public');
        }

        if ($request->input('remove_banner_subcategoria')) {
            $data['banner_subcategoria'] = null;
        }

        $negocio->update($data);

        return redirect()->route('admin.negocios.edit', $negocio)
            ->with('success', 'Configuración guardada correctamente.');
    }
}
