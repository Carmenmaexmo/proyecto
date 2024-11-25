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
                            ingrediente.alergenos.map(a => {
                                // Crear una cadena con la imagen y el nombre del alérgeno
                                return `
                                    <div>
                                        <img src="data:image/jpeg;base64,${a.foto}" alt="${a.nombre}" style="width: 30px; height: auto; margin-right: 5px;">
                                        ${a.nombre}
                                    </div>
                                `;
                            }).join('') : 'Ninguno';

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
                                    <button class="btn btn-secondary btn-sm cancel-btn" style="display:none;" onclick="cancelarEdicionIngrediente(${ingrediente.idIngredientes})">Cancelar</button>
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
    cargarAlergenosTabla();  // Cargar alérgenos en una tabla
});


// Manejar el envío del formulario de nuevo ingrediente
$('#form-nuevo-ingrediente').on('submit', function (e) {
e.preventDefault(); // Evita que el formulario recargue la página

const nombre = $('#nombre').val();
const precio = parseFloat($('#precio').val());
const obligatorio = $('#obligatorio').val(); // "1" para Sí, "0" para No

// Convertir el valor de obligatorio a "opcional" o "obligatorio"
const tipo = obligatorio === "1" ? "obligatorio" : "opcional";

// Obtener los IDs de los alérgenos seleccionados
const alergenosIds = [];
$('input[name="alergenos[]"]:checked').each(function () {
    alergenosIds.push($(this).val());
});

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
        foto: fotoBase64,
        alergenos: alergenosIds // Incluir los alérgenos seleccionados
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
row.find('.cancel-btn').show();
}

// Función para cancelar la edición
function cancelarEdicionIngrediente(idIngrediente) {
const row = $(`#ingrediente-${idIngrediente}`);
row.find('.input-nombre, .input-precio, .input-obligatorio, .input-foto').hide();
row.find('.span-nombre, .span-precio, .span-obligatorio, .span-foto').show();
row.find('.save-btn').hide();
row.find('.edit-btn').show();
row.find('.cancel-btn').hide();
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

// Manejar el envío del formulario de nuevo alérgeno
$('#form-nuevo-alergeno').on('submit', function (e) {
e.preventDefault(); // Evita que el formulario recargue la página

const nombreAlergeno = $('#nombreAlergeno').val();
const descripcionAlergeno = $('#descripcionAlergeno').val();

// Obtener el archivo de la foto del alérgeno
const fotoAlergenoInput = document.getElementById('fotoAlergeno');
const fotoAlergenoFile = fotoAlergenoInput.files[0];

if (!fotoAlergenoFile) {
    alert("Por favor, selecciona una imagen para el alérgeno.");
    return;
}

// Convertir la imagen a Base64
const reader = new FileReader();
reader.onload = function (event) {
    const fotoBase64 = event.target.result.split(',')[1]; // Obtener solo la parte Base64

    // Crear el objeto de datos para enviar a la API
    const data = {
        nombre: nombreAlergeno,
        descripcion: descripcionAlergeno,
        foto: fotoBase64 // Enviar la foto en Base64
    };

    // Enviar los datos a la API para agregar el alérgeno
    $.ajax({
        url: './api/ApiAlergenos.php',
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        success: function (response) {
            alert(response.message || "Alérgeno añadido correctamente.");
            // Limpiar el formulario después de agregar el alérgeno
            $('#form-nuevo-alergeno')[0].reset();
            cargarAlergenos();
            cargarAlergenosTabla();
        },
        error: function (xhr, status, error) {
            console.error("Error al añadir alérgeno:", xhr.responseText);
            alert("Error al añadir alérgeno. Revisa los datos y vuelve a intentarlo."); 
        }
    });
};

reader.onerror = function () {
    alert("Error al leer la imagen. Por favor, intenta de nuevo."); 
};

reader.readAsDataURL(fotoAlergenoFile); 
});

// Función para cargar los alérgenos desde la API en la tabla
function cargarAlergenosTabla() {
    $.ajax({
        url: './api/ApiAlergenos.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log("Datos de alérgenos recibidos:", data);
            let alergenos = data || []; 
            let container = $('#alergenos-tbody');
            container.empty();

            if (alergenos.length === 0) {
                container.append('<tr><td colspan="5" class="text-center">No hay alérgenos disponibles.</td></tr>');
            } else {
                alergenos.forEach(function(alergeno) {
                    container.append(`
                       <tr class="alergeno-row" data-id="${alergeno.idAlergenos}">
                            <td>${alergeno.idAlergenos}</td>
                            <td>
                                <span class="span-nombre">${alergeno.nombre}</span>
                                <input type="text" class="form-control input-nombre" value="${alergeno.nombre}" style="display:none;">
                            </td>
                            <td>
                                <span class="span-foto">
                                    <img src="data:image/jpeg;base64,${alergeno.foto}" alt="Foto Alergeno" style="width: 60px; height: auto;">
                                </span>
                                <input type="file" class="form-control input-foto" style="display:none;">
                            </td>
                            <td>
                                <span class="span-descripcion">${alergeno.descripcion}</span>
                                <textarea class="form-control input-descripcion" style="display:none;">${alergeno.descripcion}</textarea>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm editar-btn" onclick="editarAlergeno(${alergeno.idAlergenos})">Editar</button>
                                <button class="btn btn-success btn-sm save-btn" style="display:none;" onclick="guardarAlergeno(${alergeno.idAlergenos})">Guardar</button>
                                <button class="btn btn-secondary btn-sm cancel-btn" style="display:none;" onclick="cancelarEdicionAlergeno(${alergeno.idAlergenos})">Cancelar</button>
                               <button class="btn btn-danger btn-sm" onclick="borrarAlergeno(${alergeno.idAlergenos})">Borrar</button>
                            </td>
                        </tr>
                    `);
                });
                
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar los alérgenos:', error);
            $('#alergenos-tbody').html('<tr><td colspan="5" class="text-center">Error al cargar los alérgenos.</td></tr>');
        }
    });
}

// Activar el modo de edición para un alérgeno específico
function editarAlergeno(idAlergeno) {
    console.log("Editando alérgeno con id:", idAlergeno);  // Verifica si la función se ejecuta
    
    // Intentar obtener la fila de la tabla utilizando el 'data-id' correcto
    const row = $(`tr[data-id='${idAlergeno}']`);
    
    console.log(row);  // Verifica si 'row' contiene el elemento correcto

    // Verificar si la fila está correctamente seleccionada
    if (row.length === 0) {
        console.error('No se encontró el alérgeno con id:', idAlergeno);
        return; // Salir si no se encuentra la fila
    }

    // Mostrar los inputs y ocultar los valores actuales
    row.find('.span-nombre').hide();  // Ocultar el nombre actual
    row.find('.input-nombre').show(); // Mostrar el campo de entrada para editar el nombre

    row.find('.span-foto').hide();    // Ocultar la foto actual
    row.find('.input-foto').show();   // Mostrar el campo de entrada para subir una nueva foto

    row.find('.span-descripcion').hide();  // Ocultar la descripción actual
    row.find('.input-descripcion').show(); // Mostrar el campo de entrada para editar la descripción

    // Mostrar los botones de "Guardar" y "Cancelar", ocultar el botón de "Editar"
    row.find('.editar-btn').hide();   // Ocultar el botón de editar
    row.find('.save-btn').show();     // Mostrar el botón de guardar
    row.find('.cancel-btn').show();   // Mostrar el botón de cancelar
}

// Cancelar la edición y restaurar los valores originales
function cancelarEdicionAlergeno(idAlergeno) {
    const row = $(`tr[data-id='${idAlergeno}']`);  // Cambié el selector para que use 'data-id'

    // Ocultar los campos de entrada
    row.find('.input-nombre, .input-foto, .input-descripcion').hide();

    // Mostrar los valores actuales
    row.find('.span-nombre, .span-foto, .span-descripcion').show();

    // Ocultar los botones de "Guardar" y "Cancelar", mostrar el botón de "Editar"
    row.find('.save-btn').hide();
    row.find('.cancel-btn').hide();
    row.find('.editar-btn').show();
}
// Función para guardar los cambios en un alérgeno
function guardarAlergeno(idAlergeno) {
    const row = $(`tr[data-id='${idAlergeno}']`);  

    // Obtener los valores de los campos de entrada
    const nuevoNombre = row.find('.input-nombre').val();
    const nuevaDescripcion = row.find('.input-descripcion').val();
    const nuevaFotoFile = row.find('.input-foto')[0].files[0]; // Foto cargada si se ha modificado

    // Verificar que los valores se están obteniendo correctamente
    console.log('Nuevo Nombre:', nuevoNombre); // Depuración
    console.log('Nueva Descripción:', nuevaDescripcion); // Depuración
    console.log('Foto Nueva:', nuevaFotoFile ? 'Sí' : 'No'); // Depuración

    // Verificar si los valores de nombre y descripcion están vacíos
    if (!nuevoNombre || !nuevaDescripcion) {
        alert('El nombre y la descripción son obligatorios.');
        return; // Salir de la función si faltan datos
    }

    const datos = {
        idAlergenos: idAlergeno,
        nombre: nuevoNombre,
        descripcion: nuevaDescripcion
    };

    // Si se ha cargado una nueva foto, convertirla a base64
    if (nuevaFotoFile) {
        const reader = new FileReader();
        reader.onload = function (event) {
            const fotoBase64 = event.target.result.split(',')[1]; // Extraer solo la cadena base64
            datos.foto = fotoBase64;

            // Enviar los datos actualizados al servidor
            actualizarAlergeno(idAlergeno, datos);
        };
        reader.onerror = function () {
            alert("Error al leer la imagen. Por favor, intenta de nuevo.");
        };
        reader.readAsDataURL(nuevaFotoFile);
    } else {
        // Si no hay foto nueva, simplemente enviar los datos de texto
        actualizarAlergeno(idAlergeno, datos);
    }
}

// Función para actualizar un alérgeno en la base de datos
function actualizarAlergeno(idAlergeno, datos) {
    console.log('Datos a enviar al servidor:', datos); // Verificar que los datos son correctos
    $.ajax({
        url: `./api/ApiAlergenos.php/${idAlergeno}`,
        type: 'PUT',
        data: JSON.stringify(datos),
        contentType: 'application/json',
        success: function (response) {
            alert(response.message || "Alérgeno actualizado correctamente.");
            cargarAlergenosTabla(); // Recargar la tabla de alérgenos
        },
        error: function (xhr, status, error) {
            console.error("Error al actualizar alérgeno:", xhr.responseText);
            alert("Error al actualizar alérgeno. Revisa los datos y vuelve a intentarlo.");
        }
    });
}


// Función para borrar un alérgeno
function borrarAlergeno(idAlergeno) {
    console.log("idAlergeno a borrar:", idAlergeno); // Verifica que el idAlergeno se pase correctamente
    
    if (confirm("¿Estás seguro de que deseas eliminar este alérgeno?")) {
        $.ajax({
            url: `./api/ApiAlergenos.php/${idAlergeno}`,
            type: 'DELETE',
            success: function(response) {
                alert(response.message || "Alérgeno eliminado correctamente.");
                cargarAlergenosTabla();  // Recargar la lista de alérgenos
            },
            error: function(xhr, status, error) {
                console.error("Error al eliminar alérgeno:", xhr.responseText);
                alert("Error al eliminar alérgeno. Por favor, intenta de nuevo.");
            }
        });
    }
}




