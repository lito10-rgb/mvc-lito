<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use App\Models\Negocio;
use App\Models\Producto;
use App\Models\Rubro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
        $rubros = Rubro::orderBy('nombre')->get();
        $productos = Producto::orderBy('titulo')->limit(500)->get(['id', 'titulo', 'ruta']);

        return view('admin.cupones.index', compact('cupones', 'negocios', 'rubros', 'productos'));
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

    public function clientes(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string|max:80',
            'rubro_id' => 'nullable|exists:rubros,id',
        ]);

        $q = $request->get('q', '');
        $rubroId = $request->get('rubro_id');

        $query = User::with('profile')
            ->when($rubroId, fn($query) => $query->whereHas('rubros', fn($r) => $r->where('rubros.id', $rubroId)))
            ->when($q !== '', function ($query) use ($q) {
                $query->where(fn($w) => $w->where('nombre', 'like', "%{$q}%")
                    ->orWhere('apellidos', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%"));
            })
            ->orderBy('nombre')
            ->limit(300)
            ->get();

        return response()->json($query->map(fn($u) => [
            'id' => $u->id,
            'nombre' => trim($u->nombre . ' ' . $u->apellidos),
            'email' => $u->email,
            'celular' => $u->profile?->whatsapp ?? $u->profile?->celular ?? $u->profile?->telefono ?? '',
        ]));
    }

    public function enviar(Request $request, Cupon $cupon)
    {
        $request->validate([
            'canal' => 'required|in:correo,whatsapp',
            'todos' => 'nullable|boolean',
            'usuarios' => 'nullable|array',
            'usuarios.*' => 'integer|exists:users,id',
            'rubro_id' => 'nullable|exists:rubros,id',
            'producto_id' => 'nullable|exists:productos,id',
            'mensaje' => 'nullable|string|max:2000',
        ]);

        // Destinatarios
        if ($request->boolean('todos')) {
            $usuarios = User::with('profile')
                ->when($request->rubro_id, fn($query) => $query->whereHas('rubros', fn($r) => $r->where('rubros.id', $request->rubro_id)))
                ->get();
        } else {
            $ids = $request->input('usuarios', []);
            $usuarios = $ids ? User::with('profile')->whereIn('id', $ids)->get() : collect();
        }

        if ($usuarios->isEmpty()) {
            return back()->with('error', 'Selecciona al menos un cliente.');
        }

        $producto = $request->filled('producto_id') ? Producto::find($request->producto_id) : null;
        $negocio = $cupon->negocio ?? negocio_actual();
        $sitio = $negocio?->dominio ? 'https://' . $negocio->dominio : url('/');

        $mensajePersonalizado = trim($request->input('mensaje', ''));
        $nombreNegocio = $negocio?->nombre ?? 'nuestra tienda';

        // Mensaje base (WhatsApp / copy)
        $mensajeBase = $mensajePersonalizado !== ''
            ? $mensajePersonalizado
            : "Hola {nombre}, te tenemos un beneficio especial en {$nombreNegocio}: usa tu cupón {$cupon->codigo} con {$this->descuentoLegible($cupon)}.{$this->productoLegible($producto, $sitio)} Aprovecha en: {$sitio}";

        if ($request->canal === 'whatsapp') {
            $enlaces = $usuarios
                ->filter(fn($u) => $this->numeroWhatsapp($u))
                ->map(function ($u) use ($mensajeBase) {
                    $texto = str_replace('{nombre}', trim($u->nombre . ' ' . $u->apellidos), $mensajeBase);
                    return [
                        'nombre' => trim($u->nombre . ' ' . $u->apellidos),
                        'numero' => $this->numeroWhatsapp($u),
                        'enlace' => 'https://wa.me/' . $this->numeroWhatsapp($u) . '?text=' . rawurlencode($texto),
                    ];
                })->values();

            if ($enlaces->isEmpty()) {
                return back()->with('error', 'Ningún cliente seleccionado tiene WhatsApp registrado.');
            }

            return back()->with([
                'success' => 'Se generaron ' . $enlaces->count() . ' enlace(s) de WhatsApp. Pulsa en cada cliente para abrir la conversación.',
                'wa_enlaces' => $enlaces,
            ]);
        }

        // canal = correo
        $enviados = 0;
        $sinEmail = 0;
        foreach ($usuarios as $usuario) {
            if (!$usuario->email) {
                $sinEmail++;
                continue;
            }
            try {
                Mail::send('emails.cupon', [
                    'cupon' => $cupon,
                    'usuario' => $usuario,
                    'producto' => $producto,
                    'negocio' => $negocio,
                    'sitio' => $sitio,
                ], function ($message) use ($usuario) {
                    $message->to($usuario->email);
                    $message->subject('Tu cupón de descuento te espera');
                });
                $enviados++;
            } catch (\Throwable $e) {
                // continuar con el siguiente
            }
        }

        $msj = "{$enviados} correo(s) enviado(s).";
        if ($sinEmail) $msj .= " {$sinEmail} cliente(s) sin email registrado.";
        return back()->with('success', $msj);
    }

    private function descuentoLegible(Cupon $cupon): string
    {
        return $cupon->tipo === 'porcentaje'
            ? $cupon->valor . '% de descuento'
            : 'S/ ' . number_format($cupon->valor, 2) . ' de descuento';
    }

    private function productoLegible(?Producto $producto, string $sitio): string
    {
        if (!$producto) return '';
        $url = $producto->ruta ? $sitio . '/producto/' . $producto->ruta : $sitio;
        return ' Te recomendamos: ' . $producto->titulo . ' (' . $url . ')';
    }

    private function numeroWhatsapp(User $usuario): string
    {
        $raw = $usuario->profile?->whatsapp ?? $usuario->profile?->celular ?? $usuario->profile?->telefono ?? '';
        $digits = preg_replace('/\D+/', '', $raw);
        if (strlen($digits) < 9) return '';
        // Números peruanos (9 dígitos iniciando en 9): anteponer código 51
        if (strlen($digits) === 9 && str_starts_with($digits, '9')) {
            $digits = '51' . $digits;
        }
        return $digits;
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
