<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Ingredientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Mantenimiento de Ingredientes</h1>

        <!-- Tabla para listar ingredientes existentes -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Foto</th>
                        <th>Precio (€)</th>
                        <th>Obligatorio</th>
                        <th>Alérgenos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="ingredientes-tbody">
                    <!-- Los datos se llenarán aquí usando AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Formulario para añadir un nuevo ingrediente -->
        <div class="mt-5">
            <h2>Añadir Ingrediente</h2>
            <form id="form-nuevo-ingrediente" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre del Ingrediente</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto del Ingrediente</label>
                    <input type="file" class="form-control-file" id="foto" name="foto" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio (€)</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="obligatorio">¿Es obligatorio?</label>
                    <select class="form-control" id="obligatorio" name="obligatorio" required>
                        <option value="">Seleccione</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="alergenos">Alérgenos</label>
                    <div id="alergenos-container">
                        <!-- Los alérgenos se cargarán aquí -->
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Ingrediente</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Función para cargar los ingredientes desde la API
        function cargarIngredientes() {
            $.ajax({
                url: './api/ApiIngredientes.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Datos recibidos:', data);  // Verificar los datos en la consola
                    if (data && Array.isArray(data)) {  // Asegurarnos de que los datos son un array
                        let ingredientes = data; // Aquí no es necesario usar 'data.data' ya que estamos obteniendo directamente un array
                        let tbody = $('#ingredientes-tbody');
                        tbody.empty(); // Limpiar la tabla antes de llenarla

                        if (ingredientes.length === 0) {
                            tbody.append('<tr><td colspan="7" class="text-center">No hay ingredientes disponibles.</td></tr>');
                        } else {
                            ingredientes.forEach(ingrediente => {
                                // Comprobar si el ingrediente tiene alérgenos
                                let alergenos = ingrediente.alergenos.length > 0 ? 
                                    ingrediente.alergenos.map(a => a.nombre).join(', ') : 'Ninguno'; // Si tiene alérgenos, mostrar sus nombres, sino 'Ninguno'
                                
                                tbody.append(`
                                    <tr id="ingrediente-${ingrediente.idIngredientes}">
                                        <td>${ingrediente.idIngredientes}</td>
                                        <td><span class="span-nombre">${ingrediente.nombre}</span><input type="text" class="form-control input-nombre" value="${ingrediente.nombre}" style="display:none;"></td>
                                        <td><span class="span-foto"><img src="${ingrediente.foto}" alt="Foto Ingrediente" style="width: 50px; height: auto;"></span><input type="file" class="form-control input-foto" style="display:none;"></td>
                                        <td><span class="span-precio">${ingrediente.precio}</span><input type="number" class="form-control input-precio" value="${ingrediente.precio}" style="display:none;"></td>
                                        <td><span class="span-obligatorio">${ingrediente.tipo}</span><select class="form-control input-obligatorio" style="display:none;"><option value="1">Sí</option><option value="0">No</option></select></td>
                                        <td><span class="span-alergenos">${alergenos}</span></td>  <!-- Mostrar alérgenos -->
                                        <td>
                                            <button class="btn btn-warning btn-sm edit-btn" onclick="editarIngrediente(${ingrediente.idIngredientes})">Editar</button>
                                            <button class="btn btn-danger btn-sm" onclick="borrarIngrediente(${ingrediente.idIngredientes})">Borrar</button>
                                            <button class="btn btn-success btn-sm save-btn" onclick="guardarIngrediente(${ingrediente.idIngredientes})" style="display:none;">Guardar</button>
                                        </td>
                                    </tr>
                                `);
                            });
                        }
                    } else {
                        console.error('No se encontraron datos en la respuesta:', data);
                        $('#ingredientes-tbody').html('<tr><td colspan="7" class="text-center">Error al cargar ingredientes.</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar los ingredientes:', error);  // Depuración del error
                    $('#ingredientes-tbody').html('<tr><td colspan="7" class="text-center">Error al cargar los ingredientes.</td></tr>');
                }
            });
        }

        // Función para cargar los alérgenos disponibles
        function cargarAlergenos() {
            $.ajax({
                url: './api/ApiIngredientes.php',  // Cambia la URL por la de tu API para cargar los alérgenos
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let alergenos = data.data; // Suponemos que los datos están bajo la clave 'data'
                    let container = $('#alergenos-container');
                    container.empty();

                    alergenos.forEach(alergeno => {
                        container.append(`
                            <div class="form-check">
                                <input class="form-check-input alergeno-checkbox" type="checkbox" value="${alergeno.idAlergenos}" id="alergeno-${alergeno.idAlergenos}" name="alergenos[]">
                                <label class="form-check-label" for="alergeno-${alergeno.idAlergenos}">
                                    ${alergeno.nombre}
                                </label>
                            </div>
                        `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar los alérgenos: ', error);
                }
            });
        }

        function editarIngrediente(id) {
        const row = $(`#ingrediente-${id}`);
        
        // Mostrar los campos editables y ocultar los valores actuales
        row.find('.span-nombre').hide();
        row.find('.input-nombre').show();

        row.find('.span-foto').hide();
        row.find('.input-foto').show();

        row.find('.span-precio').hide();
        row.find('.input-precio').show();

        row.find('.span-obligatorio').hide();
        row.find('.input-obligatorio').show();

        // Mostrar botón "Guardar" y ocultar "Editar"
        row.find('.edit-btn').hide();
        row.find('.save-btn').show();
         }


        function guardarIngrediente(id) {
        const row = $(`#ingrediente-${id}`);

        // Recopilar los datos del formulario
        const nombre = row.find('.input-nombre').val();
        const precio = parseFloat(row.find('.input-precio').val());
        const tipo = row.find('.input-obligatorio').val();
        const fotoInput = row.find('.input-foto')[0].files[0]; // Archivo de foto

        // Crear el objeto de datos para la solicitud PUT
        const formData = new FormData();
        formData.append('nombre', nombre);
        formData.append('precio', precio);
        formData.append('tipo', tipo);

        if (fotoInput) {
            formData.append('foto', fotoInput);
        }

        // Enviar la solicitud PUT
        $.ajax({
            url: `./api/ApiIngredientes.php/${id}`,
            type: 'PUT',
            data: formData,
            contentType: false, // Necesario para enviar FormData
            processData: false,
            success: function(response) {
                console.log('Ingrediente actualizado:', response);

                // Recargar los datos en la tabla
                cargarIngredientes();
            },
            error: function(xhr, status, error) {
                console.error('Error al actualizar el ingrediente:', error);
            }
        });
        }


        
        // Cargar alérgenos al cargar la página
        $(document).ready(function() {
            cargarIngredientes();  // Cargar ingredientes al inicio
            cargarAlergenos();  // Cargar los alérgenos disponibles al cargar la página
        });
    </script>
</body>
</html>
