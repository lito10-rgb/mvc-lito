<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Negocio;
use Illuminate\Http\Request;

class OfertasController extends Controller
{
    public function index(Request $request)
    {
        $hasConOferta = !$request->filled('categoria_id') && !$request->filled('subcategoria_id');

        $query = Producto::with(['categoria', 'subcategoria', 'negocios']);

        if ($hasConOferta) {
            $query->where(function ($q) {
                $q->where('oferta', '>', 0)
                  ->orWhere('precioOferta', '>', 0)
                  ->orWhere('descuentoOferta', '>', 0)
                  ->orWhere('ofertaCategoria', '>', 0)
                  ->orWhere('ofertaSubcategoria', '>', 0)
                  ->orWhere(function ($sub) {
                      $sub->whereNull('ofertaCategoria')
                          ->whereHas('categoria', fn($cq) => $cq->where('oferta', '>', 0));
                  })
                  ->orWhere(function ($sub) {
                      $sub->whereNull('ofertaSubcategoria')
                          ->whereHas('subcategoria', fn($sq) => $sq->where('oferta', '>', 0));
                  });
            });
        }

        if ($request->filled('negocio_id')) {
            $query->whereHas('negocios', fn($q) => $q->where('negocio_id', $request->negocio_id));
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('subcategoria_id')) {
            $query->where('subcategoria_id', $request->subcategoria_id);
        }

        if ($request->filled('tipo')) {
            switch ($request->tipo) {
                case 'producto':
                    $query->where('oferta', '>', 0);
                    break;
                case 'precio_fijo':
                    $query->where('precioOferta', '>', 0);
                    break;
                case 'descuento_soles':
                    $query->where('descuentoOferta', '>', 0);
                    break;
                case 'excluida':
                    $query->where(function ($q) {
                        $q->where('ofertaCategoria', 0)
                          ->orWhere('ofertaSubcategoria', 0);
                    });
                    break;
                case 'vencida':
                    $query->where('finOferta', '<', now())->orWhere('finOferta', '0000-00-00 00:00:00');
                    break;
            }
        }

        $productos = $query->orderBy('titulo')->paginate(20)->withQueryString();

        $negocios = Negocio::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('orden')->get();

        $subcatsQuery = Subcategoria::orderBy('subcategoria');
        if ($request->filled('categoria_id')) {
            $subcatsQuery->where('id_categoria', $request->categoria_id);
        }
        $subcategorias = $subcatsQuery->get();

        return view('admin.ofertas.index', compact('productos', 'negocios', 'categorias', 'subcategorias'));
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $data = $request->validate([
            'oferta' => 'nullable|numeric|min:0|max:100',
            'precioOferta' => 'nullable|numeric|min:0',
            'descuentoOferta' => 'nullable|numeric|min:0',
            'finOferta' => 'nullable|date',
            'etiquetaOferta' => 'nullable|string|max:255',
        ]);

        $data['precioOferta'] = $data['precioOferta'] ?? 0;
        $data['descuentoOferta'] = $data['descuentoOferta'] ?? 0;
        $data['oferta'] = $data['oferta'] ?? 0;
        $data['etiquetaOferta'] = $data['etiquetaOferta'] ?? null;

        if (isset($data['finOferta']) && $data['finOferta']) {
            $data['finOferta'] = $data['finOferta'] . ' 23:59:59';
        } else {
            $data['finOferta'] = null;
        }

        $producto->update($data);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Oferta actualizada']);
        }

        return redirect()->back()->with('success', 'Oferta de "' . $producto->titulo . '" actualizada');
    }

    public function quitarOferta($id)
    {
        $producto = Producto::findOrFail($id);

        $tieneOfertaPropia = $producto->oferta > 0 || $producto->precioOferta > 0 || $producto->descuentoOferta > 0;
        $tieneOfertaCat = ($producto->categoria->oferta ?? 0) > 0;
        $tieneOfertaSub = ($producto->subcategoria->oferta ?? 0) > 0;

        $data = [
            'oferta' => 0,
            'precioOferta' => 0,
            'descuentoOferta' => 0,
            'finOferta' => null,
            'etiquetaOferta' => null,
        ];

        if ($tieneOfertaCat && !$tieneOfertaPropia) {
            $data['ofertaCategoria'] = 0;
        }
        if ($tieneOfertaSub && !$tieneOfertaPropia) {
            $data['ofertaSubcategoria'] = 0;
        }

        $producto->update($data);

        if (\Request::wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Oferta removida']);
        }

        return redirect()->back()->with('success', 'Oferta removida de "' . $producto->titulo . '"');
    }

    public function restaurarHerencia($id)
    {
        $producto = Producto::findOrFail($id);

        $data = [];
        if ($producto->ofertaCategoria === 0) {
            $data['ofertaCategoria'] = null;
        }
        if ($producto->ofertaSubcategoria === 0) {
            $data['ofertaSubcategoria'] = null;
        }

        if (empty($data)) {
            if (\Request::wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Este producto no tiene herencia bloqueada']);
            }
            return redirect()->back()->with('error', 'Este producto no tiene herencia bloqueada');
        }

        $producto->update($data);

        if (\Request::wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Herencia restaurada']);
        }

        return redirect()->back()->with('success', 'Herencia restaurada para "' . $producto->titulo . '"');
    }

    public function restaurarHerenciaMultiple(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        $productos = Producto::whereIn('id', $request->ids)->get();
        $restored = 0;

        foreach ($productos as $producto) {
            $data = [];
            if ($producto->ofertaCategoria === 0) {
                $data['ofertaCategoria'] = null;
            }
            if ($producto->ofertaSubcategoria === 0) {
                $data['ofertaSubcategoria'] = null;
            }
            if (!empty($data)) {
                $producto->update($data);
                $restored++;
            }
        }

        if (\Request::wantsJson()) {
            return response()->json(['success' => true, 'message' => "{$restored} herencias restauradas"]);
        }

        return redirect()->back()->with('success', "{$restored} herencias restauradas");
    }

    public function quitarOfertaMultiple(Request $request)
    {
        $request->validate(['ids' => 'required|array']);

        $productos = Producto::whereIn('id', $request->ids)->with('categoria', 'subcategoria')->get();

        foreach ($productos as $producto) {
            $tieneOfertaPropia = $producto->oferta > 0 || $producto->precioOferta > 0 || $producto->descuentoOferta > 0;

            $data = [
                'oferta' => 0,
                'precioOferta' => 0,
                'descuentoOferta' => 0,
                'finOferta' => null,
                'etiquetaOferta' => null,
            ];

            if (($producto->categoria->oferta ?? 0) > 0 && !$tieneOfertaPropia) {
                $data['ofertaCategoria'] = 0;
            }
            if (($producto->subcategoria->oferta ?? 0) > 0 && !$tieneOfertaPropia) {
                $data['ofertaSubcategoria'] = 0;
            }

            $producto->update($data);
        }

        $count = count($request->ids);

        if (\Request::wantsJson()) {
            return response()->json(['success' => true, 'message' => "{$count} ofertas removidas"]);
        }

        return redirect()->back()->with('success', "{$count} ofertas removidas");
    }

    public function toggleEnvio(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $gratis = (int) $request->input('gratis', 1);
        $producto->update(['envio_gratis' => $gratis]);

        $msg = $gratis ? "Envío gratuito activado para \"{$producto->titulo}\"" : "Envío gratuito desactivado para \"{$producto->titulo}\"";
        return response()->json(['success' => true, 'message' => $msg]);
    }

    public function toggleEnvioMultiple(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        $gratis = (int) $request->input('gratis', 1);

        Producto::whereIn('id', $request->ids)->update(['envio_gratis' => $gratis]);

        $count = count($request->ids);
        $msg = $gratis ? "{$count} productos con envío gratuito" : "{$count} productos sin envío gratuito";
        return response()->json(['success' => true, 'message' => $msg]);
    }
}
