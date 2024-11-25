<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Kebabs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body style="padding-top: 80px;">
    <div class="container mt-5" style="padding-bottom: 50px;">
        <h1 class="text-center mb-4">Mantenimiento de Kebabs</h1>

        <!-- Tabla para listar kebabs -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Foto</th>
                        <th>Precio (€)</th>
                        <th>Descripción</th>
                        <th>Ingredientes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="kebabs-tbody">
                    <!-- Los datos se llenarán aquí usando AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Formulario para añadir un nuevo kebab -->
        <div class="mt-5">
        <form id="form-nuevo-kebab">
            <h3>Añadir Kebab</h3>
            <div class="form-group">
                <label for="nombreKebab">Nombre:</label>
                <input type="text" id="nombreKebab" class="form-control" placeholder="Nombre del kebab">
            </div>
            <div class="form-group">
                <label for="precioKebab">Precio:</label>
                <input type="number" id="precioKebab" class="form-control" placeholder="Precio del kebab" step="0.01">
            </div>
            <div class="form-group">
                <label for="descripcionKebab">Descripción:</label>
                <textarea id="descripcionKebab" class="form-control" placeholder="Descripción del kebab"></textarea>
            </div>
            <div class="form-group">
                <label for="fotoKebab">Foto:</label>
                <input type="file" id="fotoKebab" class="form-control">
            </div>
            <div class="form-group">
                <label>Ingredientes:</label>
                <div id="ingredientes-container">
                    <!-- Los ingredientes se cargarán dinámicamente -->
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Kebab</button>
        </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/mantenimientoKebab.js"></script>
</body>
</html>