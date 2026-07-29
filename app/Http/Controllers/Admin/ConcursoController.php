<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concurso;
use App\Models\ConcursoParticipante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConcursoController extends Controller
{
    public function index()
    {
        $concursos = Concurso::withCount('participantes')->orderBy('id', 'desc')->paginate(10);
        return view('admin.concursos.index', compact('concursos'));
    }

    public function create()
    {
        return view('admin.concursos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha_sorteo' => 'required|date|after:today',
            'premio' => 'nullable|string|max:255',
        ]);

        $validated['estado'] = 'borrador';
        Concurso::create($validated);

        return redirect()->route('admin.concursos.index')->with('success', 'Concurso creado correctamente.');
    }

    public function show(Concurso $concurso)
    {
        $concurso->load(['participantes.user', 'ganador.user']);
        $pendientes = $concurso->participantes->where('email_enviado', false)->count();
        return view('admin.concursos.show', compact('concurso', 'pendientes'));
    }

    public function destroy(Concurso $concurso)
    {
        $concurso->delete();
        return back()->with('success', 'Concurso eliminado.');
    }

    public function activar(Concurso $concurso)
    {
        $concurso->update(['estado' => 'activo']);
        return back()->with('success', 'Concurso activado.');
    }

    public function finalizar(Concurso $concurso)
    {
        $concurso->update(['estado' => 'finalizado']);
        return back()->with('success', 'Concurso finalizado.');
    }

    public function generarParticipantes(Concurso $concurso, Request $request)
    {
        $request->validate([
            'alcance' => 'required|in:compradores,todos,seleccionados',
            'seleccionados' => 'nullable|array',
            'seleccionados.*' => 'exists:users,id',
        ]);

        $alcance = $request->input('alcance');
        $userIds = [];

        switch ($alcance) {
            case 'compradores':
                $userIds = User::where('modo', 'cliente')
                    ->where(function ($q) {
                        $q->whereHas('cotizaciones', fn($q2) => $q2->where('estado', 'completada'))
                          ->orWhereHas('orders');
                    })
                    ->pluck('id')->toArray();
                break;
            case 'todos':
                $userIds = User::where('modo', 'cliente')->pluck('id')->toArray();
                break;
            case 'seleccionados':
                $userIds = $request->input('seleccionados', []);
                break;
        }

        $existentes = $concurso->participantes->pluck('user_id')->toArray();
        $nuevos = array_diff($userIds, $existentes);

        $created = 0;
        foreach ($nuevos as $uid) {
            ConcursoParticipante::create([
                'concurso_id' => $concurso->id,
                'user_id' => $uid,
                'codigo' => strtoupper(bin2hex(random_bytes(4))),
            ]);
            $created++;
        }

        return back()->with('success', "{$created} participante(s) agregado(s).");
    }

    public function enviarCorreos(Concurso $concurso)
    {
        $pendientes = $concurso->participantes()->where('email_enviado', false)->with('user')->get();
        $enviados = 0;

        foreach ($pendientes as $participante) {
            $user = $participante->user;
            if (!$user || !$user->email) continue;

            try {
                Mail::send('emails.concurso', ['concurso' => $concurso, 'participante' => $participante, 'user' => $user], function ($message) use ($user, $concurso) {
                    $message->to($user->email)
                        ->subject("Participa en nuestro sorteo: {$concurso->nombre}");
                });

                $participante->update(['email_enviado' => true]);
                $enviados++;
            } catch (\Exception $e) {
                // continue
            }
        }

        return back()->with('success', "{$enviados} correo(s) enviado(s).");
    }

    public function reenviarCorreos(Concurso $concurso)
    {
        $todos = $concurso->participantes()->with('user')->get();
        $enviados = 0;

        foreach ($todos as $participante) {
            $user = $participante->user;
            if (!$user || !$user->email) continue;

            try {
                Mail::send('emails.concurso_recordatorio', ['concurso' => $concurso, 'participante' => $participante, 'user' => $user], function ($message) use ($user, $concurso) {
                    $message->to($user->email)
                        ->subject("Recordatorio: Sorteo {$concurso->nombre} — Tu código es {$participante->codigo}");
                });
                $enviados++;
            } catch (\Exception $e) {
                // continue
            }
        }

        return back()->with('success', "{$enviados} recordatorio(s) enviado(s).");
    }

    public function validarCodigo(Request $request)
    {
        $request->validate(['codigo' => 'required|string']);

        $participante = ConcursoParticipante::where('codigo', strtoupper($request->input('codigo')))
            ->with(['concurso', 'user'])
            ->first();

        if (!$participante) {
            return view('admin.concursos.sorteo', [
                'resultado' => 'no_existe',
                'codigo' => $request->input('codigo'),
            ]);
        }

        if ($participante->ganador) {
            return view('admin.concursos.sorteo', [
                'resultado' => 'ganador',
                'participante' => $participante,
                'codigo' => $request->input('codigo'),
            ]);
        }

        return view('admin.concursos.sorteo', [
            'resultado' => 'valido',
            'participante' => $participante,
            'codigo' => $request->input('codigo'),
        ]);
    }

    public function sorteo()
    {
        $concursos = Concurso::where('estado', 'activo')->withCount('participantes')->get();
        return view('admin.concursos.sorteo', ['concursos' => $concursos]);
    }

    public function declararGanador(Concurso $concurso, ConcursoParticipante $participante)
    {
        $participante->update(['ganador' => true]);
        $concurso->update([
            'ganador_participante_id' => $participante->id,
            'estado' => 'finalizado',
        ]);

        return response()->json([
            'success' => true,
            'nombre' => $participante->user->nombre . ' ' . $participante->user->apellidos,
            'codigo' => $participante->codigo,
        ]);
    }

    public function sorteoAutomatico(Concurso $concurso)
    {
        $participantes = $concurso->participantes()->where('ganador', false)->get();

        if ($participantes->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No hay participantes disponibles.']);
        }

        $ganador = $participantes->random();

        $ganador->update(['ganador' => true]);
        $concurso->update([
            'ganador_participante_id' => $ganador->id,
            'estado' => 'finalizado',
        ]);

        return response()->json([
            'success' => true,
            'nombre' => $ganador->user->nombre . ' ' . $ganador->user->apellidos,
            'codigo' => $ganador->codigo,
        ]);
    }
}
