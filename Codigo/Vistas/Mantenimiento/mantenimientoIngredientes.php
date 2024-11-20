<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Ingredientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5" style="padding-bottom: 50px; padding-top: 80px;">
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
        <!-- Formulario para añadir un nuevo alérgeno -->
        <div class="mt-5">
            <h2>Añadir Alérgeno</h2>
            <form id="form-nuevo-alergeno" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombreAlergeno">Nombre del Alérgeno</label>
                    <input type="text" class="form-control" id="nombreAlergeno" name="nombreAlergeno" required>
                </div>
                <div class="form-group">
                    <label for="fotoAlergeno">Foto del Alérgeno</label>
                    <input type="file" class="form-control-file" id="fotoAlergeno" name="fotoAlergeno" required>
                </div>
                <div class="form-group">
                    <label for="descripcionAlergeno">Descripción</label>
                    <textarea class="form-control" id="descripcionAlergeno" name="descripcionAlergeno" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Alérgeno</button>
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
                    console.log('Datos recibidos:', data);  
                    if (data && Array.isArray(data)) {
                        let ingredientes = data;
                        let tbody = $('#ingredientes-tbody');
                        tbody.empty();

                        if (ingredientes.length === 0) {
                            tbody.append('<tr><td colspan="7" class="text-center">No hay ingredientes disponibles.</td></tr>');
                        } else {
                            ingredientes.forEach(ingrediente => {
                            let alergenos = ingrediente.alergenos.length > 0 ? 
                                ingrediente.alergenos.map(a => a.nombre).join(', ') : 'Ninguno';

                            // Agrega el prefijo adecuado para mostrar la imagen
                            tbody.append(`
                                <tr id="ingrediente-${ingrediente.idIngredientes}">
                                    <td>${ingrediente.idIngredientes}</td>
                                    <td>
                                        <span class="span-nombre">${ingrediente.nombre}</span>
                                        <input type="text" class="form-control input-nombre" value="${ingrediente.nombre}" style="display:none;">
                                    </td>
                                    <td>
                                        <span class="span-foto">
                                            <img src="data:image/jpeg;base64,${ingrediente.foto}" alt="Foto Ingrediente" style="width: 60px; height: auto;">
                                        </span>
                                        <input type="file" class="form-control input-foto" style="display:none;">
                                    </td>
                                    <td>
                                        <span class="span-precio">${ingrediente.precio}</span>
                                        <input type="number" class="form-control input-precio" value="${ingrediente.precio}" style="display:none;">
                                    </td>
                                    <td>
                                        <span class="span-obligatorio">${ingrediente.tipo === "obligatorio" ? "Sí" : "No"}</span>
                                        <select class="form-control input-obligatorio" style="display:none;">
                                            <option value="1" ${ingrediente.tipo === "1" ? "selected" : ""}>Sí</option>
                                            <option value="0" ${ingrediente.tipo === "0" ? "selected" : ""}>No</option>
                                        </select>
                                    </td>
                                    <td><span class="span-alergenos">${alergenos}</span></td>
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
                    console.error('Error al cargar los ingredientes:', error);
                    $('#ingredientes-tbody').html('<tr><td colspan="7" class="text-center">Error al cargar los ingredientes.</td></tr>');
                }
            });
        }

        // Función para cargar los alérgenos desde la API
        function cargarAlergenos() {
            $.ajax({
                url: './api/ApiAlergenos.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("Datos de alérgenos recibidos:", data);
                    let alergenos = data || []; // Asegurarse de que los alérgenos sean un array
                    let container = $('#alergenos-container');
                    container.empty();  // Limpiar el contenedor de alérgenos

                    alergenos.forEach(function(alergeno) {
                        container.append(`
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="${alergeno.idAlergenos}" id="alergeno-${alergeno.idAlergenos}" name="alergenos[]">
                                <label class="form-check-label" for="alergeno-${alergeno.idAlergenos}">
                                    ${alergeno.nombre}
                                </label>
                            </div>
                        `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error al cargar los alérgenos:', error);
                }
            });
        }

        $(document).ready(function() {
            cargarIngredientes();  // Cargar ingredientes al inicio
            cargarAlergenos();  // Cargar alérgenos al cargar la página
        });

        
        // Manejar el envío del formulario de nuevo ingrediente
        $('#form-nuevo-ingrediente').on('submit', function (e) {
        e.preventDefault(); // Evita que el formulario recargue la página

        const nombre = $('#nombre').val();
        const precio = parseFloat($('#precio').val());
        const obligatorio = $('#obligatorio').val(); // "1" para Sí, "0" para No

        // Convertir el valor de obligatorio a "opcional" o "obligatorio"
        const tipo = obligatorio === "1" ? "obligatorio" : "opcional";

        const fotoInput = document.getElementById('foto');
        const fotoFile = fotoInput.files[0];

        if (!fotoFile) {
            alert("Por favor, selecciona una imagen.");
            return;
        }

        // Convertir la imagen a Base64
        const reader = new FileReader();
        reader.onload = function (event) {
            const fotoBase64 = event.target.result.split(',')[1]; // Obtén solo la parte base64

            // Crear el objeto de datos para enviar a la API
            const data = {
                nombre,
                precio,
                tipo, // Aquí se pasa como "opcional" o "obligatorio"
                foto: fotoBase64
            };

            // Enviar datos a la API
            $.ajax({
                url: './api/ApiIngredientes.php',
                type: 'POST',
                data: JSON.stringify(data),
                contentType: 'application/json',
                success: function (response) {
                    alert(response.message || "Ingrediente añadido correctamente.");
                    cargarIngredientes(); // Recargar la lista de ingredientes
                    $('#form-nuevo-ingrediente')[0].reset(); // Reiniciar el formulario
                },
                error: function (xhr, status, error) {
                    console.error("Error al añadir ingrediente:", xhr.responseText);
                    alert("Error al añadir ingrediente. Revisa los datos y vuelve a intentarlo.");
                }
            });
        };

        reader.onerror = function () {
            alert("Error al leer el archivo. Por favor, intenta de nuevo.");
        };

        reader.readAsDataURL(fotoFile); // Leer el archivo como Data URL
    });

    

    // Función para activar el modo de edición en un ingrediente
    function editarIngrediente(idIngrediente) {
        const row = $(`#ingrediente-${idIngrediente}`);
        row.find('.span-nombre, .span-precio, .span-obligatorio, .span-foto').hide();
        row.find('.input-nombre, .input-precio, .input-obligatorio, .input-foto').show();
        row.find('.edit-btn').hide();
        row.find('.save-btn').show();
    }

    // Función para cancelar la edición
    function cancelarEdicion(idIngrediente) {
        const row = $(`#ingrediente-${idIngrediente}`);
        row.find('.input-nombre, .input-precio, .input-obligatorio, .input-foto').hide();
        row.find('.span-nombre, .span-precio, .span-obligatorio, .span-foto').show();
        row.find('.save-btn').hide();
        row.find('.edit-btn').show();
    }

    // Función para guardar los cambios en el ingrediente
    function guardarIngrediente(idIngrediente) {
        const row = $(`#ingrediente-${idIngrediente}`);
        const nuevoNombre = row.find('.input-nombre').val();
        const nuevoPrecio = parseFloat(row.find('.input-precio').val());
        const nuevoObligatorio = row.find('.input-obligatorio').val(); // "1" o "0"
        const nuevoFotoFile = row.find('.input-foto')[0].files[0]; // Archivo de imagen
        console.log(row.find('.input-obligatorio').val());
        const tipo = nuevoObligatorio === "1" ? "obligatorio" : "opcional";

        // Preparar los datos básicos
        const datos = {
            idIngredientes: idIngrediente,
            nombre: nuevoNombre,
            precio: nuevoPrecio,
            tipo
        };

        // Si hay una nueva foto, convertirla a Base64 antes de enviar
        if (nuevoFotoFile) {
            const reader = new FileReader();
            reader.onload = function (event) {
                const fotoBase64 = event.target.result.split(',')[1]; // Obtener solo la parte Base64
                datos.foto = fotoBase64;

                // Enviar datos a la API con la foto convertida
                actualizarIngrediente(idIngrediente, datos);
            };

            reader.onerror = function () {
                alert("Error al leer la imagen. Por favor, intenta de nuevo.");
            };

            reader.readAsDataURL(nuevoFotoFile); // Leer el archivo como Data URL
        } else {
            // Si no hay nueva foto, enviar datos sin la propiedad "foto"
            actualizarIngrediente(idIngrediente, datos);
        }
    }

    // Función para enviar los datos actualizados a la API
    function actualizarIngrediente(idIngrediente, datos) {
        $.ajax({
            url: `./api/ApiIngredientes.php/${idIngrediente}`,
            type: 'PUT',
            data: JSON.stringify(datos),
            contentType: 'application/json',
            success: function (response) {
                alert(response.message || "Ingrediente actualizado correctamente.");
                cargarIngredientes(); // Recargar la lista de ingredientes
            },
            error: function (xhr, status, error) {
                console.error("Error al actualizar ingrediente:", xhr.responseText);
                alert("Error al actualizar ingrediente. Revisa los datos y vuelve a intentarlo.");
            }
        });
    }

    // Función para eliminar un ingrediente
    function borrarIngrediente(idIngrediente) {
        // Confirmar si el usuario está seguro de eliminar el ingrediente
        if (confirm("¿Estás seguro de que deseas eliminar este ingrediente?")) {
            // Enviar la solicitud DELETE a la API
            $.ajax({
                url: `./api/ApiIngredientes.php/${idIngrediente}`,
                type: 'DELETE',
                success: function(response) {
                    alert(response.message || "Ingrediente eliminado correctamente.");
                    // Recargar la lista de ingredientes para reflejar la eliminación
                    cargarIngredientes();
                },
                error: function(xhr, status, error) {
                    console.error("Error al eliminar ingrediente:", xhr.responseText);
                    alert("Error al eliminar ingrediente. Por favor, intenta de nuevo.");
                }
            });
        }
    }


    </script>
</body>
</html>
