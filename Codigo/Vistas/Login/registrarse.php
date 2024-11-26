<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ6A3QJ7eEoy3d6nCmGJ4u7GnZl6lx6Jq0A6BXY5iYmI5NK9O3qhl9A8rRkH" crossorigin="anonymous">
    <style src="./css/registrarse.css"></style>
</head>
<body>

    <div class="register-container">
        <h2 class="register-title">Registrarse</h2>
        <form id="registerForm">
            <!-- Campo Nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
                <div class="error-message" id="errorNombre"></div>
            </div>

            <!-- Campo Teléfono -->
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="tel" class="form-control" id="telefono" name="telefono" pattern="[0-9]{9}" title="Ingrese un número de 9 dígitos" required>
                <div class="error-message" id="errorTelefono"></div>
            </div>

            <!-- Campo Correo -->
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" required>
                <div class="error-message" id="errorCorreo"></div>
            </div>

            <!-- Campo Contraseña -->
            <div class="mb-3">
                <label for="contraseña" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="contraseña" name="contraseña" minlength="8" required>
                <div class="error-message" id="errorContraseña"></div>
            </div>

            <!-- Confirmar Contraseña -->
            <div class="mb-3">
                <label for="confirmarContraseña" class="form-label">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="confirmarContraseña" name="confirmarContraseña" minlength="8" required>
                <div class="error-message" id="errorConfirmarContraseña"></div>
            </div>

            <button type="submit" class="btn btn-primary btn-register">Registrarse</button>
            <div id="successMessage" style="display:none; color: green; font-weight: bold;"></div>
            
        </form>
        <div class="mt-3 text-center">
            <a href="?menu=login">¿Ya tienes cuenta? Inicia Sesión</a>
        </div>
    </div>

    <script src="../Codigo/js/registro.js"></script> <!-- Vinculamos el archivo JS externo -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gyb6I3Q2neW9jY1l4+O3vFY7vZwggD2+1P6c+WfA4dPnFoeGm3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0v8Fq39J4kA4mI86eDvv6c2e26g4j4R30zGzH4hR9a4wVVo1" crossorigin="anonymous"></script>
</body>
</html>
