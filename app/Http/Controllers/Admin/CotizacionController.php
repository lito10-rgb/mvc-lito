<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\CondicionesComerciale;
use App\Models\Cotizacion;
use App\Models\EmpresaLogo;
use App\Models\Producto;
use App\Models\Role;
use App\Models\Rubro;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserScore;
use App\Models\CorreoEnviado;
use App\Models\PlantillaCorreo;
use App\Helpers\PdfHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class CotizacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Cotizacion::orderBy('id', 'desc');

        if ($search = $request->get('cliente')) {
            $query->where('cliente', 'like', "%{$search}%");
        }

        $cotizaciones = $query->paginate(10)->withQueryString();

        return view('admin.cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $productos = Producto::with('categoria', 'subcategoria')->orderBy('titulo')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $usuarios = User::with(['profile', 'scores', 'roles', 'rubros'])->orderBy('nombre')->get();
        $roles = Role::orderBy('nombre')->get();
        $rubros = Rubro::orderBy('nombre')->get();
        $emisores = User::whereHas('roles', fn($q) => $q->whereIn('nombre', ['admin', 'superadmin']))
            ->orWhere('modo', 'admin')
            ->orderBy('nombre')->get();
        $logos = EmpresaLogo::orderBy('por_defecto', 'desc')->orderBy('nombre')->get();
        $condiciones = CondicionesComerciale::where('activo', true)->orderBy('titulo')->get();
        return view('admin.cotizaciones.create', compact(
            'productos', 'categorias', 'usuarios', 'roles', 'rubros', 'emisores', 'logos', 'condiciones'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha'          => 'required|date',
            'cliente'        => 'required|string|max:255',
            'telefono'       => 'nullable|string|max:50',
            'correo'         => 'nullable|email|max:255',
            'productos'      => 'required|array|min:1',
            'productos.*.producto' => 'required|string|max:255',
            'productos.*.descripcion' => 'nullable|string',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'productos.*.producto_id' => 'nullable|integer|exists:productos,id',
            'impuesto'       => 'nullable|numeric|min:0',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'condiciones'    => 'nullable|string',
            'estado'         => 'required|in:pendiente,aprobada,rechazada,completada',
        ]);

        $items = collect($validated['productos'])->map(function ($item) {
            $subtotal = $item['cantidad'] * $item['precio_unitario'];
            $portada = null;
            if (!empty($item['producto_id'])) {
                $prod = Producto::find($item['producto_id']);
                $portada = $prod ? $prod->portada : null;
            }
            return [
                'producto' => $item['producto'],
                'descripcion' => $item['descripcion'] ?? '',
                'cantidad' => (int) $item['cantidad'],
                'precio_unitario' => (float) $item['precio_unitario'],
                'subtotal' => $subtotal,
                'producto_id' => $item['producto_id'] ?? null,
                'portada' => $portada,
            ];
        })->toArray();

        $subtotal = array_sum(array_column($items, 'subtotal'));
        $impuesto = $validated['impuesto'] ?? 0;
        $descuento_pct = $validated['descuento_porcentaje'] ?? 0;
        $descuento_monto = $subtotal * ($descuento_pct / 100);
        $total = $subtotal + $impuesto - $descuento_monto;

        $first = $items[0];

        $emisorData = null;
        if ($request->emisor_id) {
            $emisor = User::with('profile')->find($request->emisor_id);
            if ($emisor) {
                $emisorData = [
                    'nombre' => $emisor->nombre . ' ' . $emisor->apellidos,
                    'email' => $emisor->email,
                    'empresa' => $emisor->profile->empresa ?? '',
                    'telefono' => $emisor->profile->telefono ?? '',
                    'direccion' => $emisor->profile->direccion ?? '',
                ];
            }
        }

        Cotizacion::create([
            'fecha' => $validated['fecha'],
            'cliente' => $validated['cliente'],
            'telefono' => $validated['telefono'],
            'correo' => $validated['correo'],
            'producto' => $first['producto'],
            'descripcion' => $first['descripcion'],
            'cantidad' => $first['cantidad'],
            'precio_unitario' => $first['precio_unitario'],
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'descuento_porcentaje' => $descuento_pct,
            'descuento_monto' => $descuento_monto,
            'total' => max($total, 0),
            'estado' => $validated['estado'],
            'productos_json' => $items,
            'condiciones' => $validated['condiciones'],
            'emisor_id' => $request->emisor_id,
            'emisor_data' => $emisorData,
            'logo_id' => $request->logo_id,
            'condicion_id' => $request->condicion_id,
            'cliente_id' => $request->cliente_id,
        ]);

        return redirect()->route('admin.cotizaciones.index')->with('success', 'Cotización creada correctamente.');
    }

    public function show(Cotizacion $cotizacione)
    {
        $plantillas = PlantillaCorreo::where('activo', true)->orderBy('nombre')->get();
        return view('admin.cotizaciones.show', compact('cotizacione', 'plantillas'));
    }

    public function edit(Cotizacion $cotizacione)
    {
        $productos = Producto::with('categoria', 'subcategoria')->orderBy('titulo')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $usuarios = User::with(['profile', 'scores', 'roles', 'rubros'])->orderBy('nombre')->get();
        $roles = Role::orderBy('nombre')->get();
        $rubros = Rubro::orderBy('nombre')->get();
        $emisores = User::whereHas('roles', fn($q) => $q->whereIn('nombre', ['admin', 'superadmin']))
            ->orWhere('modo', 'admin')
            ->orderBy('nombre')->get();
        $logos = EmpresaLogo::orderBy('por_defecto', 'desc')->orderBy('nombre')->get();
        $condiciones = CondicionesComerciale::where('activo', true)->orderBy('titulo')->get();
        return view('admin.cotizaciones.edit', compact(
            'cotizacione', 'productos', 'categorias', 'usuarios', 'roles', 'rubros', 'emisores', 'logos', 'condiciones'
        ));
    }

    public function update(Request $request, Cotizacion $cotizacione)
    {
        $validated = $request->validate([
            'fecha'          => 'required|date',
            'cliente'        => 'required|string|max:255',
            'telefono'       => 'nullable|string|max:50',
            'correo'         => 'nullable|email|max:255',
            'productos'      => 'required|array|min:1',
            'productos.*.producto' => 'required|string|max:255',
            'productos.*.descripcion' => 'nullable|string',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'productos.*.producto_id' => 'nullable|integer|exists:productos,id',
            'impuesto'       => 'nullable|numeric|min:0',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'condiciones'    => 'nullable|string',
            'estado'         => 'required|in:pendiente,aprobada,rechazada,completada',
        ]);

        $items = collect($validated['productos'])->map(function ($item) {
            $subtotal = $item['cantidad'] * $item['precio_unitario'];
            $portada = null;
            if (!empty($item['producto_id'])) {
                $prod = Producto::find($item['producto_id']);
                $portada = $prod ? $prod->portada : null;
            }
            return [
                'producto' => $item['producto'],
                'descripcion' => $item['descripcion'] ?? '',
                'cantidad' => (int) $item['cantidad'],
                'precio_unitario' => (float) $item['precio_unitario'],
                'subtotal' => $subtotal,
                'producto_id' => $item['producto_id'] ?? null,
                'portada' => $portada,
            ];
        })->toArray();

        $subtotal = array_sum(array_column($items, 'subtotal'));
        $impuesto = $validated['impuesto'] ?? 0;
        $descuento_pct = $validated['descuento_porcentaje'] ?? 0;
        $descuento_monto = $subtotal * ($descuento_pct / 100);
        $total = $subtotal + $impuesto - $descuento_monto;

        $first = $items[0];

        $emisorData = $cotizacione->emisor_data;
        if ($request->emisor_id && $request->emisor_id != $cotizacione->emisor_id) {
            $emisor = User::with('profile')->find($request->emisor_id);
            if ($emisor) {
                $emisorData = [
                    'nombre' => $emisor->nombre . ' ' . $emisor->apellidos,
                    'email' => $emisor->email,
                    'empresa' => $emisor->profile->empresa ?? '',
                    'telefono' => $emisor->profile->telefono ?? '',
                    'direccion' => $emisor->profile->direccion ?? '',
                ];
            }
        }

        $cotizacione->update([
            'fecha' => $validated['fecha'],
            'cliente' => $validated['cliente'],
            'telefono' => $validated['telefono'],
            'correo' => $validated['correo'],
            'producto' => $first['producto'],
            'descripcion' => $first['descripcion'],
            'cantidad' => $first['cantidad'],
            'precio_unitario' => $first['precio_unitario'],
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'descuento_porcentaje' => $descuento_pct,
            'descuento_monto' => $descuento_monto,
            'total' => max($total, 0),
            'estado' => $validated['estado'],
            'productos_json' => $items,
            'condiciones' => $validated['condiciones'],
            'emisor_id' => $request->emisor_id,
            'emisor_data' => $emisorData,
            'logo_id' => $request->logo_id,
            'condicion_id' => $request->condicion_id,
            'cliente_id' => $request->cliente_id,
        ]);

        return redirect()->route('admin.cotizaciones.index')->with('success', 'Cotización actualizada correctamente.');
    }

    public function destroy(Cotizacion $cotizacione)
    {
        $cotizacione->delete();
        return back()->with('success', 'Cotización eliminada.');
    }

    public function print(Cotizacion $cotizacione)
    {
        return view('admin.cotizaciones.print', compact('cotizacione'));
    }

    public function crearCliente(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email',
            'telefono' => 'nullable|string|max:20',
        ]);

        $password = substr(md5(uniqid()), 0, 10);
        $fecha = now()->format('Y-m-d H:i:s');

        $user = User::create([
            'nombre' => $validated['nombre'],
            'apellidos' => $validated['apellidos'] ?? '',
            'email' => $validated['email'] ?? '',
            'password' => Hash::make($password),
            'modo' => 'cliente',
            'fecha' => $fecha,
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'telefono' => $validated['telefono'] ?? '',
        ]);

        UserScore::create([
            'user_id' => $user->id,
            'puntuacion' => 0,
            'nivel' => 'Básico',
        ]);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'apellidos' => $user->apellidos,
                'email' => $user->email,
            ],
        ]);
    }

    public function enviarCorreo(Request $request, Cotizacion $cotizacione)
    {
        $validated = $request->validate([
            'para' => 'required|email',
            'cc' => 'nullable|email',
            'asunto' => 'required|string|max:255',
            'contenido' => 'required|string',
            'plantilla_id' => 'nullable|exists:plantillas_correo,id',
            'adjuntos' => 'nullable|array',
            'adjuntos.*' => 'file|max:10240',
        ]);

        $reemplazar = function ($texto) use ($cotizacione) {
            $vars = [
                '{cliente}' => $cotizacione->cliente,
                '{telefono}' => $cotizacione->telefono ?? '',
                '{correo}' => $cotizacione->cliente?->email ?? $cotizacione->correo ?? '',
                '{total}' => 'S/ ' . number_format($cotizacione->total, 2),
                '{fecha}' => $cotizacione->fecha->format('d/m/Y'),
                '{id}' => $cotizacione->id,
            ];
            return str_replace(array_keys($vars), array_values($vars), $texto);
        };

        $asuntoFinal = $reemplazar($validated['asunto']);
        $contenidoFinal = $reemplazar($validated['contenido']);

        $data = [
            'contenido' => nl2br(e($contenidoFinal)),
            'cotizacion' => $cotizacione,
        ];

        $para = $validated['para'];
        $cc = $validated['cc'] ?? null;

        $pdfPath = null;

        try {
            // generar PDF con Chrome headless
            $chromePath = 'C:\Program Files\Google\Chrome\Application\chrome.exe';
            if (file_exists($chromePath)) {
                $html = View::make('admin.cotizaciones.print', compact('cotizacione'))->render();
                $pdfName = 'cotizacion_' . $cotizacione->id . '.pdf';
                $pdfStoragePath = 'temp/' . $pdfName;
                if (PdfHelper::generateFromHtml($html, $pdfStoragePath)) {
                    $pdfPath = Storage::disk('public')->path($pdfStoragePath);
                }
            }

            Mail::send('emails.cotizacion', $data, function ($message) use ($para, $cc, $asuntoFinal, $pdfPath, $request) {
                $message->to($para)
                    ->subject($asuntoFinal);
                if ($cc) {
                    $message->cc($cc);
                }
                if ($pdfPath && file_exists($pdfPath)) {
                    $message->attach($pdfPath, ['as' => basename($pdfPath), 'mime' => 'application/pdf']);
                }
                if ($request->hasFile('adjuntos')) {
                    foreach ((array) $request->file('adjuntos') as $file) {
                        if ($file && $file->isValid()) {
                            $message->attach($file->getRealPath(), [
                                'as' => $file->getClientOriginalName(),
                                'mime' => $file->getMimeType(),
                            ]);
                        }
                    }
                }
            });

            CorreoEnviado::create([
                'cotizacion_id' => $cotizacione->id,
                'para' => $para,
                'cc' => $cc,
                'asunto' => $asuntoFinal,
                'contenido' => $contenidoFinal,
                'enviado_ok' => true,
            ]);

            return response()->json(['success' => true, 'message' => 'Correo enviado correctamente.']);
        } catch (\Exception $e) {
            CorreoEnviado::create([
                'cotizacion_id' => $cotizacione->id,
                'para' => $para,
                'cc' => $cc,
                'asunto' => $asuntoFinal,
                'contenido' => $contenidoFinal,
                'enviado_ok' => false,
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false, 'message' => 'Error al enviar: ' . $e->getMessage()], 500);
        } finally {
            if ($pdfPath && file_exists($pdfPath)) {
                @unlink($pdfPath);
            }
        }
    }
}
