<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Kebabs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Mantenimiento de Kebabs</h1>

        <!-- Tabla para listar los kebabs existentes -->
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
                <tbody>
                    <!-- Ejemplo de datos, estos se generarían dinámicamente desde la base de datos -->
                    <tr>
                        <td>1</td>
                        <td>Kebab Especial</td>
                        <td><img src="../imagenes/kebab-especial.jpg" alt="Foto Kebab" style="width: 50px; height: auto;"></td>
                        <td>6.50</td>
                        <td>Delicioso kebab con salsa especial</td>
                        <td>Pollo, Lechuga, Tomate, Salsa</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarKebab(1)">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="borrarKebab(1)">Borrar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Formulario para añadir un nuevo kebab -->
        <div class="mt-5">
            <h2>Añadir Kebab</h2>
            <form id="form-nuevo-kebab" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre del Kebab</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto del Kebab</label>
                    <input type="file" class="form-control-file" id="foto" name="foto" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio (€)</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="ingredientes">Ingredientes (separados por coma)</label>
                    <input type="text" class="form-control" id="ingredientes" name="ingredientes" required>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Kebab</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Simulación de funciones de mantenimiento
        function editarKebab(id) {
            alert('Editar Kebab ID: ' + id);
            // Aquí se puede redirigir a un formulario con los datos precargados
        }

        function borrarKebab(id) {
            if (confirm('¿Estás seguro de que deseas borrar este kebab?')) {
                alert('Kebab ID ' + id + ' borrado.');
                // Aquí se debe implementar el código para borrar el kebab de la base de datos
            }
        }

        document.getElementById('form-nuevo-kebab').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Nuevo kebab añadido.');
            // Aquí se debe implementar el código para añadir el kebab a la base de datos
        });
    </script>
</body>
</html>
