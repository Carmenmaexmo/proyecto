<!-- Formulario HTML para iniciar sesión -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ6A3QJ7eEoy3d6nCmGJ4u7GnZl6lx6Jq0A6BXY5iYmI5NK9O3qhl9A8rRkH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .login-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-login {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <h2 class="login-title">Iniciar Sesión</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                <label class="form-check-label" for="rememberMe">Recuérdame</label>
            </div>
            <button type="submit" class="btn btn-primary btn-login">Iniciar Sesión</button>
            <div id="error-message">Usuario o contraseña incorrectos</div>
        </form>
        <div class="mt-3 text-center">
            <a href="?menu=registrarse">¿No tienes cuenta? Regístrate</a>
        </div>
    </div>

    <script src="../Codigo/js/iniciosesion.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb6I3Q2neW9jY1l4+O3vFY7vZwggD2+1P6c+WfA4dPnFoeGm3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8Fq39J4kA4mI86eDvv6c2e26g4j4R30zGzH4hR9a4wVVo1" crossorigin="anonymous"></script>
</body>
</html>
