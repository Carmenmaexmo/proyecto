<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Usuarios</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/mantenimiento.css">
</head>
<body style="padding-top: 80px;">
    <div class="container mt-5">
        <h1 class="text-center mb-4">Mantenimiento de Usuarios</h1>

        <!-- Tabla para listar usuarios existentes -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID Usuario</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Ubicación</th>
                        <th>Teléfono</th>
                        <th>Rol</th>
                        <th>Alergenos</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuarios-tbody">
                    <!-- Los datos se llenarán aquí usando AJAX -->
                </tbody>
            </table>
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/mantenimientoUsuarios.js"></script>
</body>
</html>
