<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Ingredientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/mantenimiento.css">
</head>
<body style="padding-top: 80px;">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

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
                                
                                tbody.append(`
                                    <tr id="ingrediente-${ingrediente.idIngredientes}">
                                        <td>${ingrediente.idIngredientes}</td>
                                        <td><span class="span-nombre">${ingrediente.nombre}</span><input type="text" class="form-control input-nombre" value="${ingrediente.nombre}" style="display:none;"></td>
                                        <td><span class="span-foto"><img src="${ingrediente.foto}" alt="Foto Ingrediente" style="width: 50px; height: auto;"></span><input type="file" class="form-control input-foto" style="display:none;"></td>
                                        <td><span class="span-precio">${ingrediente.precio}</span><input type="number" class="form-control input-precio" value="${ingrediente.precio}" style="display:none;"></td>
                                        <td><span class="span-obligatorio">${ingrediente.tipo === "1" ? "Sí" : "No"}</span><select class="form-control input-obligatorio" style="display:none;"><option value="1">Sí</option><option value="0">No</option></select></td>
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

        document.querySelector('#ingredient-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    // Obtenemos el formulario y sus datos
    const formData = new FormData(e.target);
    const fotoInput = formData.get('foto');

    // Si hay imagen seleccionada, procesamos el archivo
    if (fotoInput) {
        // Generamos un nombre único para la foto (timestamp + nombre de archivo)
        const photoName = Date.now() + "_" + fotoInput.name;
        formData.set('foto', photoName); // Cambiamos el campo foto por el nombre generado

        // Subimos la imagen
        await uploadImage(fotoInput, photoName);
    } else {
        formData.set('foto', null); // Si no se ha seleccionado una imagen, enviamos null
    }

    // Enviamos los datos a la API
    const response = await fetch('/api/ingredientes', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    if (result.status === "success") {
        alert("Ingrediente creado exitosamente!");
    } else {
        alert("Error al crear ingrediente: " + result.message);
    }
});

// Función para subir la imagen al servidor
async function uploadImage(file, fileName) {
    const formData = new FormData();
    formData.append('foto', file, fileName); // Aseguramos que se guarde con el nombre único

    const response = await fetch('/upload_image.php', { 
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    if (!response.ok || result.status !== 'success') {
        throw new Error("Error al subir la imagen");
    }
}


    </script>
</body>
</html>
