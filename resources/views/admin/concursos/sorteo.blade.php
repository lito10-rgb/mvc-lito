<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorteo en Vivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #1a1a2e; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .sorteo-box { background: #16213e; border-radius: 20px; padding: 50px; max-width: 600px; width: 100%; box-shadow: 0 0 40px rgba(245,158,11,0.3); }
        .sorteo-box h1 { color: #f59e0b; text-align: center; font-size: 36px; margin-bottom: 10px; }
        .sorteo-box p { color: #94a3b8; text-align: center; font-size: 16px; }
        .codigo-input { background: #0f3460; border: 3px solid #f59e0b; color: #fff; font-size: 48px; text-align: center; letter-spacing: 10px; font-weight: bold; border-radius: 15px; padding: 20px; }
        .codigo-input::placeholder { color: #475569; letter-spacing: 3px; font-size: 24px; }
        .codigo-input:focus { box-shadow: 0 0 20px rgba(245,158,11,0.5); border-color: #fbbf24; }
        .btn-verificar { background: linear-gradient(135deg, #f59e0b, #ef4444); border: none; color: #fff; font-size: 22px; padding: 15px 40px; border-radius: 12px; font-weight: bold; width: 100%; }
        .btn-verificar:hover { transform: scale(1.02); box-shadow: 0 5px 20px rgba(245,158,11,0.4); }
        .resultado { border-radius: 15px; padding: 30px; text-align: center; margin-top: 20px; }
        .resultado-ganador { background: linear-gradient(135deg, #059669, #10b981); color: #fff; animation: pulse 1s infinite; }
        .resultado-valido { background: #1e3a5f; color: #38bdf8; }
        .resultado-no_existe { background: #4a1d1d; color: #fca5a5; }
        @keyframes pulse { 0%, 100% { box-shadow: 0 0 0 0 rgba(16,185,129,0.4); } 50% { box-shadow: 0 0 0 20px rgba(16,185,129,0); } }
        .confetti { position: fixed; top: -10px; font-size: 24px; animation: fall linear forwards; pointer-events: none; z-index: 999; }
        @keyframes fall { to { transform: translateY(110vh) rotate(720deg); opacity: 0; } }
    </style>
</head>
<body>
<div class="sorteo-box">
    <h1>SORTEO EN VIVO</h1>
    @if(isset($concursos) && $concursos->count() > 0)
        @foreach($concursos as $c)
        <p>{{ $c->nombre }} — {{ $c->premio ?: 'Premio especial' }} — {{ $c->fecha_sorteo->format('d/m/Y') }}</p>
        @endforeach
    @endif

    <form method="POST" action="{{ route('admin.concursos.validarCodigo') }}">
        @csrf
        <div class="mb-4">
            <input type="text" name="codigo" class="form-control codigo-input" placeholder="CÓDIGO"
                   value="{{ $codigo ?? '' }}" maxlength="8" autofocus required
                   style="text-transform:uppercase;">
        </div>
        <button type="submit" class="btn btn-verificar">VERIFICAR CÓDIGO</button>
    </form>

    @if(isset($resultado))
    <div class="resultado resultado-{{ $resultado }}">
        @if($resultado === 'ganador')
            <div style="font-size:60px;">WINNER</div>
            <h2 style="font-size:32px;">{{ $participante->user->nombre }} {{ $participante->user->apellidos }}</h2>
            <p style="font-size:18px;">Código: <strong>{{ $participante->codigo }}</strong></p>
            <p style="font-size:16px;">¡FELICIDADES!</p>
        @elseif($resultado === 'valido')
            <div style="font-size:50px;">CORRECTO</div>
            <h3>{{ $participante->user->nombre }} {{ $participante->user->apellidos }}</h3>
            <p>Código <strong>{{ $participante->codigo }}</strong> es válido</p>
            <p class="mt-2" style="font-size:14px;">Este participante está registrado en el sorteo.</p>
        @elseif($resultado === 'no_existe')
            <div style="font-size:50px;">NO VÁLIDO</div>
            <p>El código <strong>{{ $codigo }}</strong> no existe.</p>
        @endif
    </div>
    @endif
</div>

@if(isset($resultado) && $resultado === 'ganador')
<script>
function createConfetti() {
    const emojis = ['Confetti','Party Popper','Champagne','Gold Medal','Star','Trophy'];
    const symbols = ['Confetti','Party Popper','Gold Medal'];
    for (let i = 0; i < 50; i++) {
        const el = document.createElement('div');
        el.className = 'confetti';
        el.style.left = Math.random() * 100 + 'vw';
        el.style.animationDuration = (2 + Math.random() * 3) + 's';
        el.style.animationDelay = Math.random() * 2 + 's';
        el.textContent = ['Confetti','Gold Medal','Star','Trophy'][Math.floor(Math.random()*4)];
        el.textContent = '\uD83C\uDF89';
        document.body.appendChild(el);
    }
}
createConfetti();
setInterval(createConfetti, 3000);
</script>
@endif
</body>
</html>
