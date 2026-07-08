<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #343a40 0%, #212529 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 12px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
        }
        .login-card h3 {
            font-weight: 700;
            color: #343a40;
        }
        .login-card .form-control:focus {
            border-color: #343a40;
            box-shadow: 0 0 0 0.2rem rgba(52,58,64,.15);
        }
        .btn-dark-custom {
            background-color: #343a40;
            color: #fff;
            font-weight: 600;
        }
        .btn-dark-custom:hover {
            background-color: #23272b;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <i class="fas fa-crown fa-3x text-warning mb-2"></i>
            <h3>Panel Admin</h3>
            <p class="text-muted">Ingresa tus credenciales</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger py-2">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Recordarme</label>
            </div>
            <button type="submit" class="btn btn-dark-custom w-100 py-2">
                <i class="fas fa-sign-in-alt me-2"></i> Ingresar
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ url('/') }}" class="text-muted small">
                <i class="fas fa-arrow-left me-1"></i> Volver al sitio
            </a>
        </div>
    </div>
</body>
</html>
