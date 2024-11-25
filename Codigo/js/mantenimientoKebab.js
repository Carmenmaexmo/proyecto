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
                    // Mostrar los ingredientes como texto (vista inicial)
                    // Mostrar los ingredientes como texto (vista inicial)
                    let ingredientesTexto = kebab.ingredientes.length > 0
                    ? kebab.ingredientes.map(i => i.nombre).join(', ')
                    : 'Ninguno';

                    // Crear los checkboxes para la edición (ocultos por defecto)
                    let ingredientesCheckboxes = '';
                    $.ajax({
                    url: './api/ApiIngredientes.php',
                    type: 'GET',
                    dataType: 'json',
                    async: false,
                    success: function(ingredientesDisponibles) {
                        ingredientesDisponibles.forEach(ingrediente => {
                            const isChecked = kebab.ingredientes.some(i => i.idIngredientes === ingrediente.idIngredientes);
                            ingredientesCheckboxes += `
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="${ingrediente.idIngredientes}" ${isChecked ? 'checked' : ''}>
                                    <label class="form-check-label">${ingrediente.nombre}</label>
                                </div>
                            `;
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error al cargar ingredientes:', error);
                    }
                    });

                    tbody.append(`
                        <tr id="kebab-${kebab.idKebab}" data-id="${kebab.idKebab}">
                            <td>${kebab.idKebab}</td>
                            <td>
                                <span class="span-nombre">${kebab.nombre}</span>
                                <input type="text" class="form-control input-nombre" value="${kebab.nombre}" style="display: none;">
                            </td>
                            <td>
                                <span class="span-foto">
                                    <img src="data:image/jpeg;base64,${kebab.foto}" alt="Foto Kebab" style="width: 50px; height: auto;">
                                </span>
                                <input type="file" class="form-control input-foto" style="display: none;">
                            </td>
                            <td>
                                <span class="span-precio">${kebab.precio.toFixed(2)}</span>
                                <input type="number" class="form-control input-precio" value="${kebab.precio.toFixed(2)}" style="display: none;">
                            </td>
                            <td>
                                <span class="span-descripcion">${kebab.descripcion}</span>
                                <textarea class="form-control input-descripcion" style="display: none;">${kebab.descripcion}</textarea>
                            </td>
                            <td>
                                <span class="span-ingredientes">${ingredientesTexto}</span>
                                <div class="input-ingredientes" style="display: none;">
                                    ${ingredientesCheckboxes}
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm editar-btn" onclick="editarKebab(${kebab.idKebab})">Editar</button>
                                <button class="btn btn-success btn-sm save-btn" onclick="guardarKebab(${kebab.idKebab})" style="display: none;">Guardar</button>
                                <button class="btn btn-secondary btn-sm cancel-btn" onclick="cancelarEdicionKebab(${kebab.idKebab})" style="display: none;">Cancelar</button>
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

// Activar modo edición para un kebab
function editarKebab(idKebab) {
    const row = $(`#kebab-${idKebab}`);
    row.find('.span-nombre, .span-foto, .span-precio, .span-descripcion, .span-ingredientes').hide();
    row.find('.input-nombre, .input-foto, .input-precio, .input-descripcion, .input-ingredientes').show();
    row.find('.editar-btn').hide();
    row.find('.save-btn, .cancel-btn').show();
}

// Cancelar edición de un kebab
function cancelarEdicionKebab(idKebab) {
    const row = $(`#kebab-${idKebab}`);
    row.find('.input-nombre, .input-foto, .input-precio, .input-descripcion, .input-ingredientes').hide();
    row.find('.span-nombre, .span-foto, .span-precio, .span-descripcion, .span-ingredientes').show();
    row.find('.save-btn, .cancel-btn').hide();
    row.find('.editar-btn').show();
}

// Guardar los cambios realizados en un kebab
function guardarKebab(idKebab) {
    const row = $(`#kebab-${idKebab}`);
    const nuevoNombre = row.find('.input-nombre').val();
    const nuevoPrecio = parseFloat(row.find('.input-precio').val());
    const nuevaDescripcion = row.find('.input-descripcion').val();
    const nuevaFotoFile = row.find('.input-foto')[0].files[0];

    // Obtener los ingredientes seleccionados solo de los checkboxes dentro de la fila actual
    const ingredientesSeleccionados = row.find('.form-check-input:checked')
    .map(function () { return parseInt($(this).val()); })
    .get();

    console.log('Ingredientes seleccionados:', ingredientesSeleccionados);

    // Validación de datos
    if (!nuevoNombre || isNaN(nuevoPrecio) || !nuevaDescripcion) {
        alert('El nombre, precio y descripción son obligatorios.');
        return;
    }

    // Datos para enviar a la API
    const datos = {
        idKebab, // ID del kebab
        nombre: nuevoNombre,
        precio: nuevoPrecio,
        descripcion: nuevaDescripcion,
        ingredientes: ingredientesSeleccionados // Aquí pasamos los ingredientes seleccionados
    };
    console.log('Datos a enviar:', datos);


    // Procesar la foto si fue modificada
    if (nuevaFotoFile) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const fotoBase64 = event.target.result.split(',')[1]; // Convertir a Base64
            datos.foto = fotoBase64;
            actualizarKebab(datos); // Enviar los datos con la foto convertida
        };
        reader.onerror = function () {
            alert('Error al procesar la imagen.');
        };
        reader.readAsDataURL(nuevaFotoFile); // Convertir imagen a Base64
    } else {
        actualizarKebab(datos); // Enviar los datos sin foto
    }
}


// Actualizar un kebab en la base de datos
function actualizarKebab(datos) {
    $.ajax({
        url: `./api/ApiKebab.php/${datos.idKebab}`, // Endpoint con el ID del kebab
        type: 'PUT', // Método PUT para actualizar
        data: JSON.stringify(datos), // Enviamos el objeto completo en formato JSON
        contentType: 'application/json', // Indicamos que los datos son en formato JSON
        success: function (response) {
            alert(response.message || 'Kebab actualizado correctamente.');
            cargarKebabs(); // Recargamos la lista de kebabs después de la actualización
        },
        error: function (xhr, status, error) {
            console.error('Error al actualizar kebab:', error);
            alert('Error al actualizar kebab.');
        }
    });
}

// Borrar un kebab
function borrarKebab(idKebab) {
    if (confirm('¿Estás seguro de que deseas eliminar este kebab?')) {
        $.ajax({
            url: `./api/ApiKebab.php/${idKebab}`,
            type: 'DELETE',
            success: function (response) {
                alert(response.message || 'Kebab eliminado correctamente.');
                cargarKebabs();
            },
            error: function (xhr, status, error) {
                console.error('Error al eliminar kebab:', error);
                alert('Error al eliminar kebab.');
            }
        });
    }
}

// Inicializar las funciones al cargar la página
$(document).ready(function() {
    cargarKebabs();
    cargarIngredientes();
});


// Manejar el envío del formulario de nuevo kebab
$('#form-nuevo-kebab').on('submit', function (e) {
    e.preventDefault(); // Evita que el formulario recargue la página

    const nombreKebab = $('#nombreKebab').val();
    const precioKebab = parseFloat($('#precioKebab').val());
    const descripcionKebab = $('#descripcionKebab').val();

    // Obtener los ingredientes seleccionados
    const ingredientesSeleccionados = $("input[name='ingredientes[]']:checked")
        .map(function () { return parseInt($(this).val()); })
        .get();

    // Obtener el archivo de la foto del kebab
    const fotoKebabInput = document.getElementById('fotoKebab');
    const fotoKebabFile = fotoKebabInput.files[0];

    // Validar los campos obligatorios
    if (!nombreKebab || isNaN(precioKebab) || !descripcionKebab) {
        alert("Todos los campos (nombre, precio, descripción) son obligatorios.");
        return;
    }

    if (!fotoKebabFile) {
        alert("Por favor, selecciona una imagen para el kebab.");
        return;
    }

    // Convertir la imagen a Base64
    const reader = new FileReader();
    reader.onload = function (event) {
        const fotoBase64 = event.target.result.split(',')[1]; // Obtener solo la parte Base64

        // Crear el objeto de datos para enviar a la API
        const data = {
            nombre: nombreKebab,
            precio: precioKebab,
            descripcion: descripcionKebab,
            foto: fotoBase64,
            ingredientes: ingredientesSeleccionados
        };

        // Enviar los datos a la API para agregar el kebab
        $.ajax({
            url: './api/ApiKebab.php',
            type: 'POST',
            data: JSON.stringify(data),
            contentType: 'application/json',
            success: function (response) {
                alert(response.message || "Kebab añadido correctamente.");
                // Limpiar el formulario después de agregar el kebab
                $('#form-nuevo-kebab')[0].reset();
                cargarKebabs(); // Recargar la lista de kebabs
            },
            error: function (xhr, status, error) {
                console.error("Error al añadir kebab:", xhr.responseText);
                alert("Error al añadir kebab. Revisa los datos y vuelve a intentarlo.");
            }
        });
    };
    reader.onerror = function () {
        alert("Error al procesar la imagen. Por favor, intenta de nuevo.");
    };
    reader.readAsDataURL(fotoKebabFile);
});

