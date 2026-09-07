<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CuponController extends Controller
{
    public function index(Request $request)
    {
        $query = Cupon::with('negocio');

        if ($request->filled('buscar')) {
            $query->where('codigo', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('estado')) {
            $query->where('activo', $request->estado === 'activo');
        }

        $cupones = $query->orderByDesc('id')->paginate(20)->withQueryString();
        $negocios = Negocio::orderBy('nombre')->get();

        return view('admin.cupones.index', compact('cupones', 'negocios'));
    }

    public function create()
    {
        $negocios = Negocio::orderBy('nombre')->get();
        return view('admin.cupones.create', compact('negocios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:50|unique:cupones,codigo',
            'tipo' => 'required|in:porcentaje,monto_fijo',
            'valor' => 'required|numeric|min:0.01',
            'min_compra' => 'nullable|numeric|min:0',
            'max_usos' => 'nullable|integer|min:1',
            'negocio_id' => 'nullable|exists:negocios,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'nullable|boolean',
        ]);

        $data['codigo'] = strtoupper(Str::slug($data['codigo'], ''));
        $data['min_compra'] = $data['min_compra'] ?? 0;
        $data['activo'] = $request->boolean('activo', true);

        if (!empty($data['fecha_inicio'])) {
            $data['fecha_inicio'] = $data['fecha_inicio'] . ' 00:00:00';
        }
        if (!empty($data['fecha_fin'])) {
            $data['fecha_fin'] = $data['fecha_fin'] . ' 23:59:59';
        }

        Cupon::create($data);

        return redirect()->route('admin.cupones.index')->with('success', 'Cupón "' . $data['codigo'] . '" creado');
    }

    public function edit(Cupon $cupon)
    {
        $negocios = Negocio::orderBy('nombre')->get();
        return view('admin.cupones.edit', compact('cupon', 'negocios'));
    }

    public function update(Request $request, Cupon $cupon)
    {
        $data = $request->validate([
            'codigo' => 'required|string|max:50|unique:cupones,codigo,' . $cupon->id,
            'tipo' => 'required|in:porcentaje,monto_fijo',
            'valor' => 'required|numeric|min:0.01',
            'min_compra' => 'nullable|numeric|min:0',
            'max_usos' => 'nullable|integer|min:1',
            'negocio_id' => 'nullable|exists:negocios,id',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'activo' => 'nullable|boolean',
        ]);

        $data['codigo'] = strtoupper(Str::slug($data['codigo'], ''));
        $data['min_compra'] = $data['min_compra'] ?? 0;
        $data['activo'] = $request->boolean('activo', true);

        if (!empty($data['fecha_inicio'])) {
            $data['fecha_inicio'] = $data['fecha_inicio'] . ' 00:00:00';
        } else {
            $data['fecha_inicio'] = null;
        }
        if (!empty($data['fecha_fin'])) {
            $data['fecha_fin'] = $data['fecha_fin'] . ' 23:59:59';
        } else {
            $data['fecha_fin'] = null;
        }

        $cupon->update($data);

        return redirect()->route('admin.cupones.index')->with('success', 'Cupón actualizado');
    }

    public function destroy(Cupon $cupon)
    {
        $cupon->delete();
        return redirect()->route('admin.cupones.index')->with('success', 'Cupón eliminado');
    }

    public function toggle(Cupon $cupon)
    {
        $cupon->update(['activo' => !$cupon->activo]);
        $estado = $cupon->activo ? 'activado' : 'desactivado';

        if (\Request::wantsJson()) {
            return response()->json(['success' => true, 'activo' => $cupon->activo]);
        }

        return redirect()->back()->with('success', 'Cupón ' . $estado);
    }

    public function validar(Request $request)
    {
        $request->validate(['codigo' => 'required|string']);

        $cupon = Cupon::where('codigo', strtoupper($request->codigo))->first();

        if (!$cupon) {
            return response()->json(['valido' => false, 'mensaje' => 'Cupón no encontrado']);
        }

        if (!$cupon->estaVigente()) {
            $razon = 'Cupón no vigente';
            if (!$cupon->activo) $razon = 'Cupón desactivado';
            elseif ($cupon->fecha_fin && now()->gt($cupon->fecha_fin)) $razon = 'Cupón vencido';
            elseif ($cupon->max_usos !== null && $cupon->usos_actuales >= $cupon->max_usos) $razon = 'Cupón agotado';
            return response()->json(['valido' => false, 'mensaje' => $razon]);
        }

        $descuento = $cupon->calcularDescuento((float) $request->input('subtotal', 0));

        return response()->json([
            'valido' => true,
            'codigo' => $cupon->codigo,
            'tipo' => $cupon->tipo,
            'valor' => $cupon->valor,
            'descuento' => $descuento,
            'min_compra' => $cupon->min_compra,
            'mensaje' => $cupon->tipo === 'porcentaje'
                ? $cupon->valor . '% de descuento'
                : 'S/ ' . number_format($cupon->valor, 2) . ' de descuento',
        ]);
    }
}
