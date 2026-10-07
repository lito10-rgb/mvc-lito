<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\EmailLog;
use App\Models\Negocio;
use App\Models\PlantillaCorreo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsuarioCorreoController extends Controller
{
    public function modal(Request $request)
    {
        $ids = $request->query('ids', []);
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $ids = array_values(array_filter((array) $ids, fn ($id) => is_numeric($id)));

        $usuarios = User::with(['profile'])
            ->whereIn('id', $ids)
            ->select('id', 'nombre', 'apellidos', 'email')
            ->orderBy('id')
            ->get();

        $plantillas = PlantillaCorreo::where('activo', true)->orderBy('nombre')->get();

        $negocios = $this->negociosConEmails();

        return view('admin.usuarios.correo-modal', compact('usuarios', 'plantillas', 'negocios', 'ids'));
    }

    private function negociosConEmails()
    {
        return Negocio::orderBy('nombre')->get(['id', 'nombre', 'dominio', 'footer_email'])
            ->map(function ($n) {
                $emails = [];
                $raw = trim((string) $n->footer_email);
                if ($raw !== '') {
                    foreach (explode(';', $raw) as $item) {
                        $email = trim($item);
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $emails[] = $email;
                        }
                    }
                }
                if (!$emails && $n->dominio) {
                    $emails[] = 'informes@' . $n->dominio;
                }
                $n->emails = $emails;
                return $n;
            });
    }

    public function enviar(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'asunto' => 'required|string|max:255',
            'contenido' => 'required|string',
            'plantilla_id' => 'nullable|exists:plantillas_correo,id',
            'negocio_id' => 'nullable|exists:negocios,id',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email',
            'adjuntos' => 'nullable|array',
            'adjuntos.*' => 'file|max:10240',
            'campaign_name' => 'nullable|string|max:255',
        ]);

        $usuarios = User::with(['profile'])
            ->whereIn('id', $validated['user_ids'])
            ->whereNotNull('email')
            ->get();

        if ($usuarios->isEmpty()) {
            return back()->with('error', 'No hay usuarios con email válido seleccionados.');
        }

        $plantillaId = $request->input('plantilla_id');
        $asunto = $validated['asunto'];
        $contenido = $validated['contenido'];

        if ($plantillaId) {
            $plantilla = PlantillaCorreo::find($plantillaId);
            if ($plantilla) {
                $asunto = $plantilla->asunto;
                $contenido = $plantilla->contenido;
            }
        }

        $reemplazar = function ($texto, $user) {
            $vars = [
                '{cliente}' => trim(($user->nombre ?? '') . ' ' . ($user->apellidos ?? '')),
                '{nombre}' => $user->nombre ?? '',
                '{apellidos}' => $user->apellidos ?? '',
                '{correo}' => $user->email ?? '',
                '{telefono}' => $user->profile->telefono ?? '',
                '{empresa}' => $user->profile->empresa ?? '',
            ];
            return str_replace(array_keys($vars), array_values($vars), $texto);
        };

        $fromName = $request->input('from_name');
        $fromEmail = $request->input('from_email');

        $remitente = $this->remitenteNegocio($request);
        if ($remitente) {
            $fromName = $fromName ?: $remitente['name'];
            $fromEmail = $fromEmail ?: $remitente['email'];
        }

        $fromName = $fromName ?: config('mail.from.name');
        $fromEmail = $fromEmail ?: config('mail.from.address');

        $negocio = $request->input('negocio_id') ? Negocio::find($request->input('negocio_id')) : null;
        $batchId = Str::uuid()->toString();

        [$enviados, $fallidos] = $this->enviarMasivo($usuarios, function ($user) use ($asunto, $contenido, $reemplazar, $request, $fromName, $fromEmail, $batchId, $negocio) {
            $asuntoFinal = $reemplazar($asunto, $user);
            $contenidoFinal = $reemplazar($contenido, $user);

            try {
                Mail::send('emails.cotizacion', [
                    'contenido' => nl2br(e($contenidoFinal)),
                ], function ($message) use ($user, $asuntoFinal, $request, $fromName, $fromEmail) {
                    $message->from($fromEmail, $fromName)->to($user->email)->subject($asuntoFinal);

                    if ($request->hasFile('adjuntos')) {
                        foreach ($request->file('adjuntos') as $archivo) {
                            if ($archivo && $archivo->isValid()) {
                                $message->attach($archivo->getRealPath(), [
                                    'as' => $archivo->getClientOriginalName(),
                                    'mime' => $archivo->getMimeType(),
                                ]);
                            }
                        }
                    }
                });

                EmailLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'nombre' => trim(($user->nombre ?? '') . ' ' . ($user->apellidos ?? '')),
                    'asunto' => $asuntoFinal,
                    'contenido' => $contenidoFinal,
                    'tipo' => 'bulk',
                    'negocio' => $negocio?->nombre,
                    'from_email' => $fromEmail,
                    'from_name' => $fromName,
                    'batch_id' => $batchId,
                    'estado' => 'enviado',
                ]);
            } catch (\Throwable $e) {
                EmailLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'nombre' => trim(($user->nombre ?? '') . ' ' . ($user->apellidos ?? '')),
                    'asunto' => $asuntoFinal,
                    'contenido' => $contenidoFinal,
                    'tipo' => 'bulk',
                    'negocio' => $negocio?->nombre,
                    'from_email' => $fromEmail,
                    'from_name' => $fromName,
                    'batch_id' => $batchId,
                    'estado' => 'fallido',
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        });

        // Crear campaña si se dio nombre
        if ($request->filled('campaign_name')) {
            EmailCampaign::create([
                'nombre' => $request->input('campaign_name'),
                'tipo' => 'bulk',
                'negocio' => $negocio?->nombre,
                'batch_id' => $batchId,
                'total_enviados' => $enviados,
                'total_fallidos' => $fallidos,
                'creado_por' => Auth::id(),
            ]);
        }

        $msj = "{$enviados} correo(s) enviado(s).";
        if ($fallidos) {
            $msj .= " {$fallidos} fallido(s).";
        }

        return back()->with('success', $msj);
    }

    public function cambioPasswordModal(Request $request)
    {
        $negocioId = $request->query('negocio_id');
        $negocio = $negocioId ? Negocio::find($negocioId) : null;
        $passwordDefault = $request->query('password') ?: 'password';

        $cacheKey = 'aviso_elegibles_' . ($negocio ? $negocio->id : 'todos') . '_' . md5($passwordDefault);
        $ids = session($cacheKey);
        if (!is_array($ids)) {
            $ids = $this->elegiblesParaAviso($negocio, $passwordDefault);
            session([$cacheKey => $ids]);
        }

        $usuarios = User::whereIn('id', $ids)
            ->select('id', 'nombre', 'apellidos', 'email')
            ->orderBy('id')
            ->get();

        $plantillas = PlantillaCorreo::where('activo', true)->orderBy('nombre')->get();

        $negocios = $this->negociosConEmails();

        return view('admin.usuarios.cambio-password-modal', compact('usuarios', 'negocios', 'plantillas', 'negocioId', 'passwordDefault'));
    }

    public function enviarAvisoPassword(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'asunto' => 'required|string|max:255',
            'contenido' => 'required|string',
            'plantilla_id' => 'nullable|exists:plantillas_correo,id',
            'negocio_id' => 'nullable|exists:negocios,id',
            'password_default' => 'nullable|string|max:50',
            'from_name' => 'nullable|string|max:255',
            'from_email' => 'nullable|email',
            'adjuntos' => 'nullable|array',
            'adjuntos.*' => 'file|max:10240',
            'campaign_name' => 'nullable|string|max:255',
        ]);

        $passwordDefault = $request->input('password_default') ?: 'password';
        $negocioId = $request->input('negocio_id');
        $negocio = $negocioId ? Negocio::find($negocioId) : null;

        $cacheKey = 'aviso_elegibles_' . ($negocio ? $negocio->id : 'todos') . '_' . md5($passwordDefault);
        $elegibles = session($cacheKey);
        if (!is_array($elegibles)) {
            $elegibles = $this->elegiblesParaAviso($negocio, $passwordDefault);
            session([$cacheKey => $elegibles]);
        }

        $ids = array_values(array_intersect($validated['user_ids'], $elegibles));
        if (empty($ids)) {
            return back()->with('error', "Ninguno de los destinatarios seleccionados tiene la contraseña '{$passwordDefault}'.");
        }

        $usuarios = User::with(['profile'])->whereIn('id', $ids)->whereNotNull('email')->get();

        $plantillaId = $request->input('plantilla_id');
        $asunto = $validated['asunto'];
        $contenido = $validated['contenido'];

        if ($plantillaId) {
            $plantilla = PlantillaCorreo::find($plantillaId);
            if ($plantilla) {
                $asunto = $plantilla->asunto;
                $contenido = $plantilla->contenido;
            }
        }

        $reemplazar = function ($texto, $user) use ($passwordDefault) {
            $vars = [
                '{cliente}' => trim(($user->nombre ?? '') . ' ' . ($user->apellidos ?? '')),
                '{nombre}' => $user->nombre ?? '',
                '{apellidos}' => $user->apellidos ?? '',
                '{correo}' => $user->email ?? '',
                '{telefono}' => $user->profile->telefono ?? '',
                '{empresa}' => $user->profile->empresa ?? '',
                '{password}' => $passwordDefault,
            ];
            return str_replace(array_keys($vars), array_values($vars), $texto);
        };

        $fromName = $request->input('from_name');
        $fromEmail = $request->input('from_email');

        $remitente = $this->remitenteNegocio($request);
        if ($remitente) {
            $fromName = $fromName ?: $remitente['name'];
            $fromEmail = $fromEmail ?: $remitente['email'];
        }

        $fromName = $fromName ?: config('mail.from.name');
        $fromEmail = $fromEmail ?: config('mail.from.address');

        $batchId = Str::uuid()->toString();

        [$enviados, $fallidos] = $this->enviarMasivo($usuarios, function ($user) use ($asunto, $contenido, $reemplazar, $request, $fromName, $fromEmail, $batchId, $negocio) {
            $asuntoFinal = $reemplazar($asunto, $user);
            $contenidoFinal = $reemplazar($contenido, $user);

            try {
                Mail::send('emails.cotizacion', [
                    'contenido' => nl2br(e($contenidoFinal)),
                ], function ($message) use ($user, $asuntoFinal, $request, $fromName, $fromEmail) {
                    $message->from($fromEmail, $fromName)->to($user->email)->subject($asuntoFinal);

                    if ($request->hasFile('adjuntos')) {
                        foreach ($request->file('adjuntos') as $archivo) {
                            if ($archivo && $archivo->isValid()) {
                                $message->attach($archivo->getRealPath(), [
                                    'as' => $archivo->getClientOriginalName(),
                                    'mime' => $archivo->getMimeType(),
                                ]);
                            }
                        }
                    }
                });

                EmailLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'nombre' => trim(($user->nombre ?? '') . ' ' . ($user->apellidos ?? '')),
                    'asunto' => $asuntoFinal,
                    'contenido' => $contenidoFinal,
                    'tipo' => 'aviso_password',
                    'negocio' => $negocio?->nombre,
                    'from_email' => $fromEmail,
                    'from_name' => $fromName,
                    'batch_id' => $batchId,
                    'estado' => 'enviado',
                ]);
            } catch (\Throwable $e) {
                EmailLog::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'nombre' => trim(($user->nombre ?? '') . ' ' . ($user->apellidos ?? '')),
                    'asunto' => $asuntoFinal,
                    'contenido' => $contenidoFinal,
                    'tipo' => 'aviso_password',
                    'negocio' => $negocio?->nombre,
                    'from_email' => $fromEmail,
                    'from_name' => $fromName,
                    'batch_id' => $batchId,
                    'estado' => 'fallido',
                    'error' => $e->getMessage(),
                ]);
                throw $e;
            }
        });

        // Crear campaña si se dio nombre
        if ($request->filled('campaign_name')) {
            EmailCampaign::create([
                'nombre' => $request->input('campaign_name'),
                'tipo' => 'aviso_password',
                'negocio' => $negocio?->nombre,
                'batch_id' => $batchId,
                'total_enviados' => $enviados,
                'total_fallidos' => $fallidos,
                'creado_por' => Auth::id(),
            ]);
        }

        $msj = "Enviados {$enviados} aviso(s).";
        if ($fallidos) {
            $msj .= " {$fallidos} fallido(s).";
        }

        return back()->with('success', $msj);
    }

    private function elegiblesParaAviso($negocio, string $passwordDefault): array
    {
        $query = User::whereNotNull('email')->where('email', '<>', '');

        if ($negocio) {
            $query->where('negocio', $negocio->dominio);
        }

        $ids = [];
        foreach ($query->orderBy('id')->cursor() as $user) {
            if ($this->esPasswordDefault($user->password, $passwordDefault)) {
                $ids[] = $user->id;
            }
        }

        return $ids;
    }

    private function enviarMasivo($usuarios, callable $enviarUno): array
    {
        $lote = max(1, (int) config('correo.lote', 25));
        $pausa = max(0, (int) config('correo.pausa', 90));
        $maxTotal = max(0, (int) config('correo.max_total', 0));

        $enviados = 0;
        $fallidos = 0;
        $contador = 0;

        foreach ($usuarios as $user) {
            if ($maxTotal > 0 && $enviados >= $maxTotal) {
                break;
            }

            if ($enviados > 0 && $contador >= $lote) {
                sleep($pausa);
                $contador = 0;
            }

            try {
                $enviarUno($user);
                $enviados++;
            } catch (\Throwable $e) {
                $fallidos++;
            }

            $contador++;
        }

        return [$enviados, $fallidos];
    }

    private function esPasswordDefault(string $stored, string $candidata): bool
    {
        if ($stored === $candidata) {
            return true;
        }

        $oldSalt = '$2a$07$asxx54ahjppf45sd87a5a4dDDGsystemdev$2a$07$asxx54ahjppf45sd87a5auxq/SS293XhTEeizKWMnfhnpfay0AALe';
        if (crypt($candidata, $oldSalt) === $stored) {
            return true;
        }

        try {
            if (Hash::check($candidata, $stored)) {
                return true;
            }
        } catch (\Throwable $e) {
            // hash inválido, no coincide
        }

        return false;
    }

    private function remitenteNegocio(Request $request): ?array
    {
        $negocioId = $request->input('negocio_id');
        if (!$negocioId) {
            return null;
        }

        $negocio = Negocio::find($negocioId);
        if (!$negocio) {
            return null;
        }

        $email = $this->primerEmailNegocio($negocio);

        return [
            'name' => $negocio->nombre ?: config('mail.from.name'),
            'email' => $email,
        ];
    }

    private function primerEmailNegocio(Negocio $negocio): ?string
    {
        $raw = trim((string) $negocio->footer_email);
        if ($raw !== '') {
            foreach (explode(';', $raw) as $item) {
                $email = trim($item);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $email;
                }
            }
        }
        if ($negocio->dominio) {
            return 'informes@' . $negocio->dominio;
        }
        return null;
    }
}
