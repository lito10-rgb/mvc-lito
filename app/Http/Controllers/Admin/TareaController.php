<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Marca;
use App\Models\Proveedor;
use App\Models\Cabecera;
use App\Models\User;
use App\Models\TareaContacto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;

class TareaController extends Controller
{
    /* ===================== DASHBOARD ===================== */
    public function index()
    {
        // Permisos que el usuario actual tiene sobre los módulos de tareas
        $puede = [
            'productos'   => auth()->user()->puede('productos.crear'),
            'proveedores' => auth()->user()->puede('proveedores.gestionar'),
            'cotizaciones'=> auth()->user()->puede('cotizaciones.gestionar'),
        ];

        return view('admin.tareas.index', compact('puede'));
    }

    /* ============ TAREA 1: Productos hermanos desde cotizaciones ============ */
    public function productosCotizados()
    {
        $agrupados = $this->agruparItemsCotizados();

        return view('admin.tareas.productos_cotizados', compact('agrupados'));
    }

    // Expuesto solo para pruebas de CLI
    public function agruparItemsCotizados()
    {
        // Agrupar items únicos del productos_json de todas las cotizaciones
        $cotizaciones = Cotizacion::whereNotNull('productos_json')->orderByDesc('id')->limit(300)->get();

        $agrupados = [];
        foreach ($cotizaciones as $cot) {
            foreach ($cot->items as $item) {
                $clave = trim((string)($item['producto'] ?? ''));
                if ($clave === '') continue;
                if (!isset($agrupados[$clave])) {
                    $agrupados[$clave] = [
                        'producto'      => $clave,
                        'descripcion'   => trim((string)($item['descripcion'] ?? '')),
                        'precio_unitario' => (float)($item['precio_unitario'] ?? 0),
                        'portada'       => $item['portada'] ?? null,
                        'producto_id'   => $item['producto_id'] ?? null,
                        'veces'         => 0,
                        'cantidad_total'=> 0,
                    ];
                }
                $agrupados[$clave]['veces']++;
                $agrupados[$clave]['cantidad_total'] += (int)($item['cantidad'] ?? 1);
                if (empty($agrupados[$clave]['portada']) && !empty($item['portada'])) {
                    $agrupados[$clave]['portada'] = $item['portada'];
                }
                if (empty($agrupados[$clave]['producto_id']) && !empty($item['producto_id'])) {
                    $agrupados[$clave]['producto_id'] = $item['producto_id'];
                }
            }
        }

        usort($agrupados, fn($a, $b) => $b['veces'] <=> $a['veces']);

        return $agrupados;
    }

    // Crear producto "hermano" a partir de un item cotizado (item libre, sin producto_id)
    public function guardarHermano(Request $request)
    {
        $request->validate([
            'titulo'         => 'required|string|max:255',
            'descripcion'    => 'nullable|string',
            'precio'         => 'required|numeric|min:0',
            'portada'        => 'nullable|string|max:500',
            'categoria_id'   => 'required|exists:categorias,id',
            'subcategoria_id'=> 'required|exists:subcategorias,id',
            'marca_id'       => 'nullable|exists:marcas,id',
            'proveedor_id'   => 'nullable|exists:proveedores,id',
        ]);

        $subcategoria = Subcategoria::find($request->subcategoria_id);
        if (!$subcategoria || (int)$subcategoria->id_categoria !== (int)$request->categoria_id) {
            return back()->withInput()->withErrors([
                'subcategoria_id' => 'La subcategoría elegida no pertenece a la categoría seleccionada.'
            ]);
        }

        $titulo = trim($request->titulo);
        $portada = $request->portada;
        $multimedia = $portada ? [$portada] : [];

        $producto = Producto::create([
            'tipo'             => 'fisico',
            'ruta'             => Str::slug($titulo) . '-' . Str::lower(Str::random(5)),
            'estado'           => 1,
            'titulo'           => $titulo,
            'titular'          => $titulo,
            'descripcion'      => $request->descripcion ?: '',
            'multimedia'       => json_encode($multimedia),
            'detalles'         => '',
            'precio'           => $request->precio,
            'stock'            => 0,
            'portada'          => $portada ?: 'defaults/default-portada.jpg',
            'vistas'           => rand(10, 100),
            'ventas'           => rand(1, 30),
            'vistasGratis'     => 0,
            'ventasGratis'     => 0,
            'peso'             => null,
            'entrega'          => null,
            'costo_envio'      => null,
            'envio_gratis'     => false,
            'categoria_id'     => $request->categoria_id,
            'subcategoria_id'  => $request->subcategoria_id,
            'marca_id'         => $request->marca_id ?: null,
            'proveedor_id'     => $request->proveedor_id ?: null,
            'fecha'            => now(),
        ]);

        Cabecera::create([
            'ruta'            => $producto->ruta,
            'titulo'          => $titulo,
            'descripcion'     => $request->descripcion ?: $titulo,
            'palabras_claves' => $titulo,
            'portada'         => $producto->portada,
            'fecha'           => now(),
        ]);

        return redirect()->route('admin.tareas.productosCotizados')
            ->with('success', "Producto hermano \"{$titulo}\" creado.");
    }

    /* ============ TAREA 2: Proveedores que escribieron (Outlook) ============ */
    public function outlook()
    {
        if (!class_exists('COM')) {
            return redirect()->route('admin.tareas.index')
                ->with('error', 'La extensión COM no está activa. Activa extension=php_com_dotnet.dll en php.ini.');
        }
        if (!file_exists("C:\Program Files\Microsoft Office")) {
            return redirect()->route('admin.tareas.index')
                ->with('error', 'No se encontró Microsoft Outlook instalado en esta máquina.');
        }

        try {
            $correos = $this->leerOutlook();
        } catch (\Throwable $e) {
            Log::error('Outlook COM: ' . $e->getMessage());
            return redirect()->route('admin.tareas.index')
                ->with('error', 'No se pudo leer Outlook: ' . $e->getMessage());
        }

        // Priorizar los correos marcados con banderita (los más importantes), luego más recientes
        usort($correos, function ($a, $b) {
            if ((bool)$a['bandera'] !== (bool)$b['bandera']) {
                return $a['bandera'] ? -1 : 1;
            }
            return strcmp((string)$b['fecha'], (string)$a['fecha']);
        });

        // Reasignar idx según el orden final (para que sesión/enlaces coincidan)
        foreach ($correos as $i => $c) {
            $correos[$i]['idx'] = $i;
        }

        // Guardar en sesión para pasar detalles entre requests
        session(['tareas_outlook' => $correos]);

        // Paginación manual (12 por página) sobre el array completo
        $porPagina = 12;
        $pagina    = max(1, (int)request()->get('page', 1));
        $paginados = new LengthAwarePaginator(
            array_slice($correos, ($pagina - 1) * $porPagina, $porPagina),
            count($correos),
            $porPagina,
            $pagina,
            ['path' => request()->url(),
             'query' => request()->query()]
        );

        // Contador de contactos: cuántas veces se escribió/respondió a cada proveedor
        $contactos = TareaContacto::all();
        $porEmail = [];
        foreach ($contactos as $ct) {
            $email = strtolower(trim((string)$ct->proveedor_email));
            if ($email === '') continue;
            $porEmail[$email] = ($porEmail[$email] ?? 0) + 1;
        }

        return view('admin.tareas.outlook', compact('paginados', 'correos', 'porPagina', 'porEmail'));
    }

    private function leerOutlook($dias = 30, $limiteTotal = 2000)
    {
        $outlook = new \COM('Outlook.Application');
        $ns = $outlook->GetNamespace('MAPI');

        $corte = now()->subDays($dias)->getTimestamp();

        $correos = [];
        $contador = 0;
        $vistos = [];   // EntryID para no duplicar si llega la misma cuenta varias veces

        // Colección de bandejas de entrada: una por cada store (cuenta/POP/IMAP) configurada
        $stores = [];
        try {
            foreach ($ns->Stores as $store) {
                $stores[] = $store;
            }
        } catch (\Throwable $e) { }

        // Si no se pudo listar stores, usar la bandeja de entrada predeterminada
        if (empty($stores)) {
            $stores[] = $ns->GetDefaultFolder(6)->Store;
        }

        foreach ($stores as $store) {
            if ($contador >= $limiteTotal) break;
            $nombreCuenta = trim((string)$store->DisplayName);

            try {
                $inbox = $store->GetDefaultFolder(6); // olFolderInbox de cada cuenta
            } catch (\Throwable $e) {
                continue;
            }

            $items = $inbox->Items;
            // Ordenar por fecha recibida descendente (más recientes primero)
            try { $items->Sort('ReceivedTime', true); } catch (\Throwable $e) { }

            $leidosEnCuenta = 0;

            foreach ($items as $mail) {
                if ($contador >= $limiteTotal) break;
                $leidosEnCuenta++;
                try {
                    $entryId = '';
                    try { $entryId = trim((string)$mail->EntryID); } catch (\Throwable $e) { }
                    if ($entryId !== '' && isset($vistos[$entryId])) continue;
                    if ($entryId !== '') $vistos[$entryId] = true;

                    // Fecha recibida: si es más antigua que el corte, dejar de leer esta cuenta
                    // (la bandeja va ordenada de más reciente a más antigua)
                    $fecha = $this->parsearFechaCom($mail->ReceivedTime);
                    $ts = $fecha ? strtotime($fecha) : false;
                    if ($ts !== false && $ts < $corte) {
                        break;
                    }

                    $adjuntos = [];
                    $rutaAdjuntos = storage_path('app/tareas/outlook/' . $contador);
                    try {
                        $count = $mail->Attachments->Count;
                        for ($i = 1; $i <= $count; $i++) {
                            $adj = $mail->Attachments->Item($i);
                            $nombre = (string)$adj->FileName;
                            $rutaLocal = '';
                            if (str_ends_with(strtolower($nombre), '.pdf')) {
                                if (!is_dir($rutaAdjuntos)) {
                                    mkdir($rutaAdjuntos, 0755, true);
                                }
                                $rutaLocal = $rutaAdjuntos . '\\' . $nombre;
                                try {
                                    $adj->SaveAsFile($rutaLocal);
                                    if (!is_file($rutaLocal)) $rutaLocal = '';
                                } catch (\Throwable $e) {
                                    $rutaLocal = '';
                                }
                            }
                            $adjuntos[] = [
                                'nombre' => $nombre,
                                'ruta'   => $rutaLocal,
                            ];
                        }
                    } catch (\Throwable $e) { /* sin adjuntos */ }

                    $bandera = ((int)$mail->FlagStatus === 2);

                    // Guardar el cuerpo en disco (no en sesión, para no inflar el payload de la sesión)
                    $cuerpo = (string)($mail->Body ?? '');
                    $cuerpoRuta = '';
                    if ($cuerpo !== '') {
                        $dirCuerpos = storage_path('app/tareas/outlook/cuerpos');
                        if (!is_dir($dirCuerpos)) {
                            mkdir($dirCuerpos, 0755, true);
                        }
                        $cuerpoRuta = $dirCuerpos . '\\correo_' . $contador . '.txt';
                        @file_put_contents($cuerpoRuta, $cuerpo);
                    }

                    $correos[] = [
                        'idx'      => $contador,
                        'cuenta'   => $nombreCuenta,
                        'remitente'=> trim((string)($mail->SenderName ?? '')),
                        'email'    => trim((string)($mail->SenderEmailAddress ?? '')),
                        'asunto'   => trim((string)($mail->Subject ?? '')),
                        'fecha'    => $fecha,
                        'bandera'  => $bandera,
                        'cuerpo'   => '',
                        'cuerpo_ruta' => $cuerpoRuta,
                        'adjuntos' => $adjuntos,
                        'spam'     => $bandera ? false : $this->esCorreoSpam((string)($mail->Subject ?? ''), trim((string)($mail->SenderName ?? '')), trim((string)($mail->SenderEmailAddress ?? ''))),
                    ];
                } catch (\Throwable $e) {
                    // saltar items no aptos (reuniones, etc.)
                }
                $contador++;
            }
        }

        return $correos;
    }

    private function esCorreoSpam($asunto, $remitente, $email)
    {
        $hay = fn($texto) => fn($patron) => str_contains(mb_strtolower($texto), mb_strtolower($patron));

        $patronesAsunto = ['virus', 'solicitud de cotiz', 'unsubscribe', 'urgente', 'warning', 'alert', 'confirm your', 'delivery', 'pendiente', 'invoice', 'factura', 'login', 'password', 'you have 3', 'email space', 'importante', 'agreement-', 'action needed', 'whatsapp web', 'free', 'gana', 'gratis', 'oferta', 'descuento'];
        foreach ($patronesAsunto as $p) {
            if ($hay($asunto)($p)) return true;
        }

        $patronesEmail = ['surveymonkey', 'activechc.org', 'rumahmaris.com', 'c-m-tech.cam', 'informes@cafe-peruano', 'saintthomas.edu.do', 'muctieu.com', 'logodep.com'];
        foreach ($patronesEmail as $p) {
            if ($hay($email)($p)) return true;
        }

        if (str_contains($email, 'cafe-peruano.com') && $remitente !== '' && !str_contains(mb_strtolower($remitente), 'cliente')) return true;

        return false;
    }

    private function parsearFechaCom($valor)
    {
        if (!$valor) return null;
        $s = trim((string)$valor);

        // Ya viene en formato ISO/estándar (p.ej. 2026-06-13 14:20:26)
        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $s)) {
            try { return \Illuminate\Support\Carbon::parse($s)->format('Y-m-d H:i'); }
            catch (\Throwable $e) { return $s; }
        }

        // Formato COM "M/D/YYYY H:M:S AM/PM" o "D/M/YYYY H:M:S"
        // Estrategia: parsear con DateTime asumiendo d/m/Y primero; si falla, m/d/Y
        if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})[/T ](.*)$#', $s, $m)) {
            $d = (int)$m[1]; $mo = (int)$m[2]; $y = (int)$m[3];
            $resto = $m[4];
            if ($d > 12) {
                // día > 12 => formato D/M/Y
                try { return \Illuminate\Support\Carbon::createFromFormat('d/m/Y H:i', "$m[1]/$m[2]/$m[3] " . $this->normalizarHora($resto))->format('Y-m-d H:i'); }
                catch (\Throwable $e) { try { return date('Y-m-d H:i', strtotime($s)); } catch (\Throwable $e2) { return $s; } }
            }
            // Ambiguo: intentar d/m/Y, luego m/d/Y
            try { $ca = \Illuminate\Support\Carbon::createFromFormat('d/m/Y H:i', "$m[1]/$m[2]/$m[3] " . $this->normalizarHora($resto)); } catch (\Throwable $e) { $ca = null; }
            try { $cb = \Illuminate\Support\Carbon::createFromFormat('m/d/Y H:i', "$m[1]/$m[2]/$m[3] " . $this->normalizarHora($resto)); } catch (\Throwable $e) { $cb = null; }
            if ($ca) return $ca->format('Y-m-d H:i');
            if ($cb) return $cb->format('Y-m-d H:i');
            try { return date('Y-m-d H:i', strtotime($s)); } catch (\Throwable $e) { return $s; }
        }

        try { return \Illuminate\Support\Carbon::parse($s)->format('Y-m-d H:i'); }
        catch (\Throwable $e) { return $s; }
    }

    private function normalizarHora($hora)
    {
        // "14:20:26" o "02:20:26 PM" -> HH:MM
        $hora = trim($hora);
        if ($hora === '') return '00:00';
        if (preg_match('/(\d{1,2}):(\d{2})/', $hora, $m)) {
            $h = (int)$m[1];
            $min = (int)$m[2];
            if (preg_match('/pm/i', $hora) && $h < 12) $h += 12;
            if (preg_match('/am/i', $hora) && $h == 12) $h = 0;
            return sprintf('%02d:%02d', $h, $min);
        }
        return '00:00';
    }

    // Registrar proveedor desde un correo de la sesión
    public function inscribirProveedor(Request $request)
    {
        $correos = session('tareas_outlook', []);
        $idx = (int)$request->input('idx', -1);
        if (!isset($correos[$idx])) {
            return redirect()->route('admin.tareas.outlook')->with('error', 'Correo no encontrado, vuelve a escanear.');
        }
        $correo = $correos[$idx];

        // No duplicar si ya existe proveedor con ese email
        $existente = Proveedor::where('email', $correo['email'])->first();
        if ($existente) {
            return redirect()->route('admin.proveedores.edit', $existente)->with('info', 'Ya existe un proveedor con ese correo: ' . $existente->nombre);
        }

        $nombreEmail = Str::before($correo['email'], '@');

        $proveedor = Proveedor::create([
            'nombre'      => $correo['remitente'] ?: $nombreEmail,
            'empresa'     => $nombreEmail,
            'email'       => $correo['email'],
            'descripcion' => 'Capturado desde correo: ' . $correo['asunto'],
        ]);

        return redirect()->route('admin.proveedores.edit', $proveedor)
            ->with('success', 'Proveedor registrado desde el correo. Completa sus datos.');
    }

    // Formulario para escribir un mensaje a un proveedor (con plantilla pre-cargada)
    public function escribirCorreo($idx)
    {
        $correos = session('tareas_outlook', []);
        $idx = (int)$idx;
        if (!isset($correos[$idx])) {
            return redirect()->route('admin.tareas.outlook')->with('error', 'Correo no encontrado, vuelve a escanear.');
        }
        $correo = $correos[$idx];

        $proveedor = $correo['email'] ? Proveedor::where('email', $correo['email'])->first() : null;

        // Negocios disponibles para seleccionar la marca del mensaje
        $negocios = \App\Models\Negocio::orderBy('nombre')->get(['id', 'nombre', 'dominio']);

        // Detectar negocio por el dominio de la cuenta que recibió el correo (ej. ventas@cafe-peruano.com -> Cafe Peruano)
        $dominioCuenta = $correo['cuenta'] ?? '';
        $dominioCuenta = mb_strtolower(trim((string)Str::after($dominioCuenta, '@')));
        $negocioSeleccionado = $negocios->firstWhere('dominio', $dominioCuenta);
        if (!$negocioSeleccionado) {
            $sub = mb_strpos($dominioCuenta, '.');
            $soloTld = mb_substr($dominioCuenta, $sub === false ? 0 : $sub + 1);
            $negocioSeleccionado = $negocios->firstWhere('dominio', $soloTld) ?: $negocios->first();
        }
        $negocioSeleccionado = $negocioSeleccionado ?: $negocios->first();

        // Correos de las cuentas Outlook disponibles (para elegir el "From")
        $cuentasFrom = [];
        foreach ($correos as $c) {
            $cta = trim((string)($c['cuenta'] ?? ''));
            if ($cta !== '' && filter_var($cta, FILTER_VALIDATE_EMAIL)) $cuentasFrom[$cta] = $cta;
        }
        $desde = $correo['cuenta'];
        if (!$desde && $cuentasFrom) {
            $desde = array_key_first($cuentasFrom);
        }
        if (!$desde) {
            $desde = config('mail.from.address');
        }

        // Plantilla provisional editable (para el negocio detectado)
        $plantilla = view('admin.tareas.plantilla_correo', [
            'proveedor' => $proveedor,
            'correo'    => $correo,
            'negocio'   => $negocioSeleccionado,
        ])->render();

        // Pre-renderizar plantilla por cada negocio (para cambiarla desde el selector)
        $plantillasPorNegocio = [];
        foreach ($negocios as $neg) {
            $plantillasPorNegocio[$neg->id] = view('admin.tareas.plantilla_correo', [
                'proveedor' => $proveedor,
                'correo'    => $correo,
                'negocio'   => $neg,
            ])->render();
        }

        $asuntoSugerido = $correo['asunto'] ? 'Re: ' . $correo['asunto'] : 'Consulta sobre sus productos';

        return view('admin.tareas.escribir_correo', compact(
            'correo', 'proveedor', 'plantilla', 'plantillasPorNegocio', 'asuntoSugerido',
            'negocios', 'negocioSeleccionado', 'cuentasFrom', 'desde'
        ));
    }

    // Enviar el mensaje redactado al proveedor
    public function enviarCorreo(Request $request)
    {
        $request->validate([
            'para'      => 'required|email',
            'nombre'    => 'nullable|string|max:255',
            'asunto'    => 'required|string|max:255',
            'mensaje'   => 'required|string',
            'negocio_id'=> 'nullable|exists:negocios,id',
            'desde'     => 'nullable|email',
        ]);

        $nombre  = trim($request->nombre);
        $para    = trim($request->para);
        $asunto  = trim($request->asunto);
        $mensaje = trim($request->mensaje);
        $desde   = trim($request->desde);
        $negocio = $request->negocio_id ? \App\Models\Negocio::find($request->negocio_id) : \App\Models\Negocio::first();
        if (!$desde && $negocio?->dominio) {
            $desde = 'informes@' . $negocio->dominio;
        }
        if (!$desde) {
            $desde = config('mail.from.address');
        }

        try {
            Mail::send('emails.tarea_mensaje', [
                'nombre'  => $nombre,
                'mensaje' => nl2br(e($mensaje)),
                'negocio' => $negocio,
            ], function ($m) use ($para, $asunto, $desde) {
                $m->from($desde)->to($para)->subject($asunto);
            });

            // Contador: registrar este contacto (escrito/respuesta) para el proveedor
            TareaContacto::create([
                'cuenta'          => $desde,
                'proveedor_email' => $para,
                'proveedor_nombre'=> $nombre,
                'negocio_id'      => $negocio?->id,
                'asunto'          => $asunto,
            ]);

            return redirect()->route('admin.tareas.outlook')
                ->with('success', "Mensaje enviado a {$para} desde {$desde}. Asunto: {$asunto}");
        } catch (\Throwable $e) {
            Log::error('tareas enviarCorreo: ' . $e->getMessage());
            return back()->withInput()->with('error', 'No se pudo enviar el correo: ' . $e->getMessage());
        }
    }

    // Ver detalle de un correo: adjuntos y catálogos a capturar
    public function correoDetalle($idx)
    {
        $correos = session('tareas_outlook', []);
        if (!isset($correos[$idx])) {
            return redirect()->route('admin.tareas.outlook')->with('error', 'Correo no encontrado, vuelve a escanear.');
        }
        $correo = $correos[$idx];

        $categorias  = Categoria::orderBy('categoria')->get();
        $marcas      = Marca::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();

        // Parsear cuerpo en líneas candidatas
        $cuerpo = '';
        if (!empty($correo['cuerpo_ruta']) && is_file($correo['cuerpo_ruta'])) {
            $cuerpo = (string)file_get_contents($correo['cuerpo_ruta']);
        } elseif (!empty($correo['cuerpo'])) {
            $cuerpo = $correo['cuerpo'];
        }
        $lineasCandidatas = $this->extraerLineasCatalogo($cuerpo);

        // Intentar extraer texto de adjuntos PDF
        $pdfTextos = [];
        foreach ($correo['adjuntos'] as $adj) {
            $nombre = is_array($adj) ? $adj['nombre'] : (string)$adj;
            $textoPdf = $this->extraerAdjuntoPdf($adj);
            if ($textoPdf !== null) {
                $pdfTextos[$nombre] = $this->extraerLineasCatalogo($textoPdf);
            }
        }

        return view('admin.tareas.correo_detalle', compact('correo', 'categorias', 'marcas', 'proveedores', 'lineasCandidatas', 'pdfTextos'));
    }

    // Guardar productos capturados del correo/catálogo (de las líneas marcadas)
    public function capturarProductos(Request $request)
    {
        $request->validate([
            'lineas'        => 'required|array|min:1',
            'lineas.*'      => 'required|string|max:500',
        ]);

        $categoria_id    = $request->input('categoria_id');
        $marca_id        = $request->input('marca_id') ?: null;
        $proveedor_id    = $request->input('proveedor_id') ?: null;
        $crearProveedor  = $request->input('crear_proveedor');
        $nombreProveedor = $request->input('nombre_proveedor');
        $emailProveedor  = $request->input('email_proveedor');

        if ($crearProveedor && $crearProveedor === '1') {
            $proveedor = Proveedor::create([
                'nombre'  => $nombreProveedor ?: 'Proveedor nuevo',
                'email'   => $emailProveedor,
                'descripcion' => 'Capturado desde catálogo por correo.',
            ]);
            $proveedor_id = $proveedor->id;
        }

        if (!$proveedor_id) {
            $proveedor_id = Proveedor::inRandomOrder()->value('id');
        }

        $portadas = $request->input('portadas', []);
        $creados = 0;
        foreach ($request->input('lineas') as $i => $linea) {
            $titulo = trim($linea);
            if ($titulo === '') continue;

            $portada = $portadas[$i] ?? null;
            $multimedia = $portada ? [$portada] : [];

            Producto::create([
                'tipo'            => 'fisico',
                'ruta'            => Str::slug($titulo) . '-' . Str::lower(Str::random(5)),
                'estado'          => 1,
                'titulo'          => $titulo,
                'titular'         => $titulo,
                'descripcion'     => '',
                'multimedia'      => json_encode($multimedia),
                'detalles'        => '',
                'precio'          => 0,
                'stock'           => 0,
                'portada'         => $portada ?: 'defaults/default-portada.jpg',
                'vistas'          => rand(10, 100),
                'ventas'          => 0,
                'vistasGratis'    => 0,
                'ventasGratis'    => 0,
                'categoria_id'    => $categoria_id ?: 1,
                'subcategoria_id' => $categoria_id ? Subcategoria::where('id_categoria', $categoria_id)->value('id') ?: 1 : 1,
                'marca_id'        => $marca_id,
                'proveedor_id'    => $proveedor_id,
                'fecha'           => now(),
            ]);
            $creados++;
        }

        return redirect()->route('admin.tareas.outlook')
            ->with('success', "{$creados} producto(s) capturado(s) del correo.");
    }

    // Registrar proveedor encontrado en internet (desde el último producto cotizado)
    public function inscribirProveedorWeb(Request $request)
    {
        $request->validate([
            'nombre'  => 'required|string|max:255',
            'web'     => 'nullable|string|max:500',
            'email'   => 'nullable|email|max:255',
            'empresa' => 'nullable|string|max:255',
            'producto'=> 'nullable|string|max:255',
        ]);

        $nombre = trim($request->nombre);
        $email  = trim((string)$request->email);

        $existente = $email !== '' ? Proveedor::where('email', $email)->first()
            : Proveedor::where('nombre', $nombre)->first();

        if ($existente) {
            return redirect()->route('admin.proveedores.edit', $existente)
                ->with('info', 'Ya existe un proveedor similar: ' . $existente->nombre . ' → edítalo para actualizar sus datos.');
        }

        $proveedor = Proveedor::create([
            'nombre'      => $nombre,
            'empresa'     => $request->empresa ?: $nombre,
            'web'         => $request->web,
            'email'       => $email ?: null,
            'descripcion' => 'Registrado desde búsqueda web' . ($request->producto ? ' (' . $request->producto . ')' : ''),
        ]);

        return redirect()->route('admin.proveedores.edit', $proveedor)
            ->with('success', 'Proveedor "' . $nombre . '" registrado desde la búsqueda web. Completa sus datos.');
    }

    // Extrae líneas candidatas a productos desde un texto de catálogo
    private function extraerLineasCatalogo($texto)
    {
        if (!$texto) return [];
        $lineas = preg_split('/\r\n|\r|\n/', $texto);
        $resultado = [];
        foreach ($lineas as $l) {
            $l = trim(preg_replace('/\s+/', ' ', $l));
            if ($l === '') continue;
            // Ignorar líneas que claramente no son productos
            if (strlen($l) < 6) continue;
            if (strlen($l) > 200) continue;
            if (preg_match('/^(http|www|tel:|mailto:|@)/i', $l)) continue;
            if (preg_match('/\.(jpg|jpeg|png|gif|pdf|zip)$/i', $l)) continue;
            if (preg_match('/^(página|pagina|page|phone|fax|whatsapp|contacto|contact)/i', $l)) continue;
            if (preg_match('/^\d+\s*$/', $l)) continue;
            $resultado[] = $l;
        }
        return array_values(array_unique($resultado));
    }

    // Guarda un adjunto temporalmente y devuelve su texto (PDF) o null
    private function extraerAdjuntoPdf($adjunto)
    {
        if (!class_exists(\Smalot\PdfParser\Parser::class)) return null;
        $ruta = is_array($adjunto) ? ($adjunto['ruta'] ?? '') : '';
        if (!$ruta || !is_file($ruta)) return null;

        try {
            $parser = new \Smalot\PdfParser\Parser();
            $texto = $parser->parseFile($ruta)->getText();
            return $texto ?: null;
        } catch (\Throwable $e) {
            Log::warning('PDFParser: ' . $e->getMessage());
            return null;
        }
    }

    /* ============ TAREA 3: Buscar proveedor por producto ============ */
    public function categoriasJson()
    {
        return response()->json(
            Categoria::orderBy('categoria')->get(['id', 'categoria'])->map(fn($c) => [
                'id' => $c->id, 'categoria' => $c->categoria
            ])
        );
    }

    public function subcategoriasDe(Categoria $categoria)
    {
        return response()->json($categoria->subcategorias->map(fn($s) => ['id' => $s->id, 'nombre' => $s->subcategoria]));
    }

    public function productosDe(Subcategoria $subcategoria)
    {
        return response()->json(
            Producto::where('subcategoria_id', $subcategoria->id)
                ->orderBy('titulo')->limit(300)
                ->get(['id', 'titulo', 'precio', 'portada'])
        );
    }

    /* ============ TAREA 4: Proveedores para el último producto cotizado ============ */
    public function ultimoCotizado()
    {
        $cotizacion = Cotizacion::orderByDesc('id')->first();

        // Candidatos encontrados por el agente (búsqueda web) para el último cotizado.
        $proveedoresWeb = [
            [
                'nombre'  => 'EnvaPack Perú',
                'origen'  => 'Nacional',
                'web'     => 'https://envapack-peru.com/producto/bolsas-fuelle-8-sellos-con-o-sin-valvula/',
                'email'   => '',
                'producto'=> 'Bolsa fuelle 8 sellos',
                'detalle' => 'Bolsa fuelle tipo caja 8 sellos con/sin válvula, 250-500g. Café tostado/verde, quinua, chía. Envíos a todo Perú, delivery gratis en Lima.',
            ],
            [
                'nombre'  => 'Envases y Empaques Elixir',
                'origen'  => 'Nacional',
                'web'     => 'https://envasesyempaqueselixir.com/index.php/producto/bolsas-para-cafe-de-8-sellos-100-unidades/',
                'email'   => '',
                'producto'=> 'Bolsa café 8 sellos 250gr con válvula',
                'detalle' => 'Bolsas base plana u 8 sellos, con/sin válvula, varios tamaños y colores. Precios desde S/ 97 (100 uds) hasta precios por 1000 uds.',
            ],
            [
                'nombre'  => 'Grafiempak',
                'origen'  => 'Internacional (México)',
                'web'     => 'https://grafiempak.com/bolsas-con-fuelle/1-1-empaque-para-cafe-fuelle-bilaminado-250g.html',
                'email'   => 'ventas@grafiempak.com',
                'producto'=> 'Empaque café fuelle bilaminado 250g',
                'detalle' => 'Bolsas fuelle con barrera UV/humedad/oxígeno, válvula desgasificadora. Tel: (961) 671 5521 / 961 615 7777.',
            ],
            [
                'nombre'  => 'YPAK (Dongguan Yupu Packaging)',
                'origen'  => 'Internacional (China)',
                'web'     => 'https://www.ypak-packaging.com/custom-coffee-bags-manufacturer-flat-bottom-coffee-pouches-with-valve-zipper-product/',
                'email'   => 'sam@ypak-packaging.com',
                'producto'=> 'Flat bottom coffee pouch 8-side seal con válvula',
                'detalle' => 'Bolsa fondo plano 8 sellos con válvula unidireccional y zipper. MOQ 2000. Tel/WhatsApp +86 13922978931.',
            ],
            [
                'nombre'  => 'Changxing Printing (made-in-china)',
                'origen'  => 'Internacional (China)',
                'web'     => 'https://cx-pack.en.made-in-china.com/',
                'email'   => '',
                'producto'=> 'Flat bottom coffee bag con válvula 250g',
                'detalle' => 'Fábrica desde 1985 (Guangdong), certificaciones FDA/ASTM/EEC/BRC. Fondo plano con válvula y zipper, 250g-1kg.',
            ],
            [
                'nombre'  => 'Bowe Pack',
                'origen'  => 'Internacional (China)',
                'web'     => 'https://bowepack.com/products/250g-coffee-bags',
                'email'   => '',
                'producto'=> '250g coffee bag flat bottom con válvula',
                'detalle' => '$0.03-$0.30 por unidad, MOQ 500-10,000 piezas. Certificación ISO/BRC/FDA, muestra gratis.',
            ],
            [
                'nombre'  => 'Lebei Packaging',
                'origen'  => 'Internacional (China)',
                'web'     => 'https://www.lebeipackaging.com/product/custom-flat-bottom-coffee-pouch-with-valve/',
                'email'   => 'sales8@lbpacking.net',
                'producto'=> 'Flat bottom coffee pouch 8-side seal con válvula',
                'detalle' => 'Estructura PET/VMPET/PE, MOQ personalizado 10,000 uds. Tel +86 15216953668.',
            ],
            [
                'nombre'  => 'MST Packaging',
                'origen'  => 'Internacional (China)',
                'web'     => 'https://www.mstpack.com/dp-250g-coffee-packaging-k1785017.html',
                'email'   => '',
                'producto'=> '250g coffee packaging flat bottom con válvula',
                'detalle' => 'Fábrica ISO22000/BRC/HACCP. Biodegradable con válvula y zipper. MOQ desde 10,000 uds.',
            ],
        ];

        return view('admin.tareas.ultimo_cotizado', compact('cotizacion', 'proveedoresWeb'));
    }

    /* ============ TAREA 5: Buscar clientes (Outlook + registrados) ============ */
    public function clientes()
    {
        // 1) Candidatos desde Outlook (remitentes, sin spam) — solo correos válidos
        $clientesOutlook = [];
        if (class_exists('COM') && file_exists("C:\Program Files\Microsoft Office")) {
            try {
                $correos = $this->leerOutlook(70);
            } catch (\Throwable $e) {
                Log::warning('clientes(Outlook): ' . $e->getMessage());
                $correos = [];
            }
            foreach ($correos as $c) {
                if ($c['spam']) continue;
                $email = strtolower(trim((string)$c['email']));
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;
                if (str_contains($email, 'cafe-peruano.com') || str_contains($email, 'equiposymaquinas.com')) continue;
                $clientesOutlook[$email] = [
                    'origen'  => 'outlook',
                    'nombre'  => $c['remitente'] ?: $email,
                    'email'   => $c['email'],
                    'asunto'  => $c['asunto'],
                    'fecha'   => $c['fecha'],
                    'bandera' => $c['bandera'],
                    'roles'   => '',
                    'user_id' => null,
                ];
            }
        }

        // 2) Usuarios registrados con email — solo los que podrían ser clientes (clientes, cotizantes, prospectos, vendedores)
        $rolesCliente = ['cliente', 'cotizante', 'prospecto', 'vendedor'];
        $ots = [];
        foreach (User::with('roles')->whereNotNull('email')->where('email', '!=', '')->orderBy('nombre')->get() as $u) {
            $roles = $u->roles->pluck('nombre')->map(fn($n) => trim((string)$n))->filter();
            $sinRol = $roles->isEmpty();
            $esCliente = $roles->contains(fn($n) => in_array($n, $rolesCliente, true));
            if ($sinRol || !$esCliente) continue; // sin rol = proveedores importados
            $ots[] = $u;
        }
        $registrados = [];
        foreach ($ots as $u) {
            $email = strtolower(trim((string)$u->email));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) continue;
            $registrados[$email] = [
                'origen'  => 'registrado',
                'nombre'  => trim(trim((string)$u->nombre) . ' ' . trim((string)($u->apellidos ?? ''))),
                'email'   => $u->email,
                'asunto'  => '',
                'fecha'   => $u->fecha ?? '',
                'bandera' => false,
                'roles'   => $u->roles->pluck('nombre')->implode(', '),
                'user_id' => $u->id,
            ];
        }

        // 3) Unir: si aparecen en ambos, gana el rango de "registrado" (más confiable)
        $clientes = $registrados;
        foreach ($clientesOutlook as $email => $dato) {
            if (isset($clientes[$email])) {
                $clientes[$email]['asunto']  = $dato['asunto'];
                $clientes[$email]['fecha']   = $dato['fecha'];
                $clientes[$email]['bandera'] = $dato['bandera']; // conserva bandera si llegó
                $clientes[$email]['origen']  = 'ambos';
            } else {
                $clientes[$email] = $dato;
            }
        }

        // Ordenar: bandera primero, luego por fecha descendente
        $clientes = array_values($clientes);
        usort($clientes, function ($a, $b) {
            if ($a['bandera'] !== $b['bandera']) return $a['bandera'] ? -1 : 1;
            return strcmp((string)$b['fecha'], (string)$a['fecha']);
        });

        $categorias = Categoria::orderBy('categoria')->get();

        return view('admin.tareas.clientes', compact('clientes', 'categorias'));
    }
}