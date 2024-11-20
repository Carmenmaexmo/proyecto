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
                    <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                </div>
                <div class="form-group">
                    <label for="ingredientes">Ingredientes</label>
                    <div id="ingredientes-container">
                        <!-- Los ingredientes se cargarán aquí -->
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Kebab</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Función para cargar los kebabs desde la API
        function cargarKebabs() {
            $.ajax({
                url: './api/ApiKebab.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Datos de kebabs recibidos:', data);
                    let tbody = $('#kebabs-tbody');
                    tbody.empty();

                    if (data.length === 0) {
                        tbody.append('<tr><td colspan="7" class="text-center">No hay kebabs disponibles.</td></tr>');
                    } else {
                        data.forEach(kebab => {
                            let ingredientes = kebab.ingredientes.length > 0 ? 
                                kebab.ingredientes.map(i => i.nombre).join(', ') : 'Ninguno';
                            
                            tbody.append(`
                                <tr id="kebab-${kebab.idKebab}">
                                    <td>${kebab.idKebab}</td>
                                    <td>${kebab.nombre}</td>
                                    <td><img src="${kebab.foto}" alt="Foto Kebab" style="width: 50px; height: auto;"></td>
                                    <td>${kebab.precio.toFixed(2)}</td>
                                    <td>${kebab.descripcion}</td>
                                    <td>${ingredientes}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" onclick="borrarKebab(${kebab.idKebab})">Borrar</button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar los kebabs:', error);
                    $('#kebabs-tbody').html('<tr><td colspan="7" class="text-center">Error al cargar kebabs.</td></tr>');
                }
            });
        }

        // Función para cargar los ingredientes desde la API
        function cargarIngredientes() {
            $.ajax({
                url: './api/ApiIngredientes.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("Datos de ingredientes recibidos:", data);
                    let container = $('#ingredientes-container');
                    container.empty();

                    data.forEach(ingrediente => {
                        container.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${ingrediente.idIngredientes}" id="ingrediente-${ingrediente.idIngredientes}" name="ingredientes[]">
                                <label class="form-check-label" for="ingrediente-${ingrediente.idIngredientes}">
                                    ${ingrediente.nombre}
                                </label>
                            </div>
                        `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar los ingredientes:', error);
                }
            });
        }

        $(document).ready(function() {
            cargarKebabs();
            cargarIngredientes();

            // Manejar el envío del formulario de nuevo kebab
            $('#form-nuevo-kebab').submit(async function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const fotoInput = formData.get('foto');

                // Si hay foto, procesarla
                if (fotoInput) {
                    const photoName = Date.now() + "_" + fotoInput.name;
                    formData.set('foto', photoName);
                    await uploadImage(fotoInput, photoName);
                }

                // Enviar datos del kebab a la API
                $.ajax({
                    url: './api/ApiKebab.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert("Kebab añadido correctamente!");
                        $('#form-nuevo-kebab')[0].reset();
                        cargarKebabs();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al añadir el kebab:', error);
                        alert("Error al añadir el kebab.");
                    }
                });
            });
        });

        // Función para subir la imagen
        async function uploadImage(file, fileName) {
            const formData = new FormData();
            formData.append('foto', file, fileName);

            const response = await fetch('./upload_image.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (!response.ok || result.status !== 'success') {
                throw new Error("Error al subir la imagen");
            }
        }

        // Función para borrar un kebab
        function borrarKebab(id) {
            if (confirm("¿Estás seguro de eliminar este kebab?")) {
                $.ajax({
                    url: `./api/ApiKebab.php?id=${id}`,
                    type: 'DELETE',
                    success: function(response) {
                        alert("Kebab eliminado correctamente.");
                        cargarKebabs();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al borrar el kebab:', error);
                        alert("Error al borrar el kebab.");
                    }
                });
            }
        }
    </script>
</body>
</html>
