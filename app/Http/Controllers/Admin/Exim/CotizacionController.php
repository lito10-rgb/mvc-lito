<?php

namespace App\Http\Controllers\Admin\Exim;

use App\Http\Controllers\Controller;
use App\Models\Exim\Cliente;
use App\Models\Exim\Contenedor;
use App\Models\Exim\Cotizacion;
use App\Models\Exim\Incoterm;
use App\Models\Exim\Moneda;
use App\Models\Exim\Producto;
use App\Models\Exim\Transporte;
use App\Models\Pais;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Cotizacion::orderBy('id', 'desc');

        if ($search = $request->get('codigo')) {
            $query->where('codigo', 'like', "%{$search}%");
        }

        if ($estado = $request->get('estado')) {
            $query->where('estado', $estado);
        }

        $cotizaciones = $query->paginate(10)->withQueryString();

        return view('admin.exim.cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $codigo = 'EXIM-' . str_pad(Cotizacion::max('id') + 1, 5, '0', STR_PAD_LEFT);

        $clientes = Cliente::orderBy('empresa')->get();
        $productosExim = Producto::orderBy('nombre')->get();
        $incoterms = Incoterm::orderBy('codigo')->get();
        $transportes = Transporte::orderBy('nombre')->get();
        $contenedores = Contenedor::orderBy('tipo')->get();
        $monedas = Moneda::orderBy('codigo')->get();
        $paises = Pais::orderBy('nombre')->get();

        return view('admin.exim.cotizaciones.create', compact(
            'codigo', 'clientes', 'productosExim', 'incoterms',
            'transportes', 'contenedores', 'monedas', 'paises'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'codigo'                   => 'required|string|max:20|unique:exim_cotizaciones,codigo',
            'cliente_id'               => 'required|integer|exists:exim_clientes,id',
            'fecha'                    => 'required|date',
            'validez_dias'             => 'nullable|integer|min:1',
            'incoterm_id'              => 'nullable|integer|exists:exim_incoterms,id',
            'transporte_id'            => 'nullable|integer|exists:exim_transportes,id',
            'contenedor_id'            => 'nullable|integer|exists:exim_contenedores,id',
            'moneda_id'                => 'required|integer|exists:exim_monedas,id',
            'tipo_cambio'              => 'nullable|numeric|min:0',
            'gastos_operativos_total'  => 'nullable|numeric|min:0',
            'gastos_logisticos_total'  => 'nullable|numeric|min:0',
            'costo_pallets'            => 'nullable|numeric|min:0',
            'costo_contenedor'         => 'nullable|numeric|min:0',
            'seguro_total'             => 'nullable|numeric|min:0',
            'documentacion_total'      => 'nullable|numeric|min:0',
            'utilidad_porcentaje'      => 'nullable|numeric|min:0',
            'utilidad_monto'           => 'nullable|numeric|min:0',
            'subtotal'                 => 'nullable|numeric|min:0',
            'total'                    => 'nullable|numeric|min:0',
            'notas'                    => 'nullable|string',
            'estado'                   => 'required|in:borrador,enviada,aprobada,rechazada',
        ]);

        $validated['gastos_operativos_total'] = (float) ($validated['gastos_operativos_total'] ?? 0);
        $validated['gastos_logisticos_total'] = (float) ($validated['gastos_logisticos_total'] ?? 0);
        $validated['costo_pallets']           = (float) ($validated['costo_pallets'] ?? 0);
        $validated['costo_contenedor']        = (float) ($validated['costo_contenedor'] ?? 0);
        $validated['seguro_total']            = (float) ($validated['seguro_total'] ?? 0);
        $validated['documentacion_total']     = (float) ($validated['documentacion_total'] ?? 0);
        $validated['subtotal']                = (float) ($validated['subtotal'] ?? 0);
        $validated['utilidad_porcentaje']     = (float) ($validated['utilidad_porcentaje'] ?? 0);
        $validated['utilidad_monto']          = (float) ($validated['utilidad_monto'] ?? 0);

        $base = $validated['subtotal']
              + $validated['gastos_operativos_total']
              + $validated['gastos_logisticos_total']
              + $validated['costo_pallets']
              + $validated['costo_contenedor']
              + $validated['seguro_total']
              + $validated['documentacion_total'];

        $validated['utilidad_monto'] = $base * ($validated['utilidad_porcentaje'] / 100);
        $validated['total']          = $base + $validated['utilidad_monto'];

        Cotizacion::create($validated);

        return redirect()->route('admin.exim.cotizaciones.index')->with('success', 'Cotización creada correctamente.');
    }

    public function show(Cotizacion $cotizacione)
    {
        return view('admin.exim.cotizaciones.show', compact('cotizacione'));
    }

    public function edit(Cotizacion $cotizacione)
    {
        $clientes = Cliente::orderBy('empresa')->get();
        $productosExim = Producto::orderBy('nombre')->get();
        $incoterms = Incoterm::orderBy('codigo')->get();
        $transportes = Transporte::orderBy('nombre')->get();
        $contenedores = Contenedor::orderBy('tipo')->get();
        $monedas = Moneda::orderBy('codigo')->get();
        $paises = Pais::orderBy('nombre')->get();

        return view('admin.exim.cotizaciones.edit', compact(
            'cotizacione', 'clientes', 'productosExim', 'incoterms',
            'transportes', 'contenedores', 'monedas', 'paises'
        ));
    }

    public function update(Request $request, Cotizacion $cotizacione)
    {
        $validated = $request->validate([
            'codigo'                   => 'required|string|max:20|unique:exim_cotizaciones,codigo,' . $cotizacione->id,
            'cliente_id'               => 'required|integer|exists:exim_clientes,id',
            'fecha'                    => 'required|date',
            'validez_dias'             => 'nullable|integer|min:1',
            'incoterm_id'              => 'nullable|integer|exists:exim_incoterms,id',
            'transporte_id'            => 'nullable|integer|exists:exim_transportes,id',
            'contenedor_id'            => 'nullable|integer|exists:exim_contenedores,id',
            'moneda_id'                => 'required|integer|exists:exim_monedas,id',
            'tipo_cambio'              => 'nullable|numeric|min:0',
            'gastos_operativos_total'  => 'nullable|numeric|min:0',
            'gastos_logisticos_total'  => 'nullable|numeric|min:0',
            'costo_pallets'            => 'nullable|numeric|min:0',
            'costo_contenedor'         => 'nullable|numeric|min:0',
            'seguro_total'             => 'nullable|numeric|min:0',
            'documentacion_total'      => 'nullable|numeric|min:0',
            'utilidad_porcentaje'      => 'nullable|numeric|min:0',
            'utilidad_monto'           => 'nullable|numeric|min:0',
            'subtotal'                 => 'nullable|numeric|min:0',
            'total'                    => 'nullable|numeric|min:0',
            'notas'                    => 'nullable|string',
            'estado'                   => 'required|in:borrador,enviada,aprobada,rechazada',
        ]);

        $validated['gastos_operativos_total'] = (float) ($validated['gastos_operativos_total'] ?? 0);
        $validated['gastos_logisticos_total'] = (float) ($validated['gastos_logisticos_total'] ?? 0);
        $validated['costo_pallets']           = (float) ($validated['costo_pallets'] ?? 0);
        $validated['costo_contenedor']        = (float) ($validated['costo_contenedor'] ?? 0);
        $validated['seguro_total']            = (float) ($validated['seguro_total'] ?? 0);
        $validated['documentacion_total']     = (float) ($validated['documentacion_total'] ?? 0);
        $validated['subtotal']                = (float) ($validated['subtotal'] ?? 0);
        $validated['utilidad_porcentaje']     = (float) ($validated['utilidad_porcentaje'] ?? 0);
        $validated['utilidad_monto']          = (float) ($validated['utilidad_monto'] ?? 0);

        $base = $validated['subtotal']
              + $validated['gastos_operativos_total']
              + $validated['gastos_logisticos_total']
              + $validated['costo_pallets']
              + $validated['costo_contenedor']
              + $validated['seguro_total']
              + $validated['documentacion_total'];

        $validated['utilidad_monto'] = $base * ($validated['utilidad_porcentaje'] / 100);
        $validated['total']          = $base + $validated['utilidad_monto'];

        $cotizacione->update($validated);

        return redirect()->route('admin.exim.cotizaciones.index')->with('success', 'Cotización actualizada correctamente.');
    }

    public function destroy(Cotizacion $cotizacione)
    {
        $cotizacione->delete();

        return redirect()->route('admin.exim.cotizaciones.index')->with('success', 'Cotización eliminada.');
    }
}
