// URL base para la API
const apiUrl = './Api/ApiUser.php';

$.ajax({
    url: apiUrl, // La URL de la API
    type: 'GET',
    dataType: 'json',
    success: function(response) {
        console.log(response); // Verifica la respuesta completa en la consola
        if (response.success && response.usuarios && Array.isArray(response.usuarios)) {
            const usuarios = response.usuarios;
            $('#usuarios-tbody').html(''); // Limpiar la tabla antes de agregar los nuevos datos

            usuarios.forEach(usuario => {
                console.log(usuario); // Verifica los datos de cada usuario
                
                // Maneja los alergenos
                let alergenos = usuario.alergenos && usuario.alergenos.length > 0 ? usuario.alergenos.join(', ') : 'No especificados';
                
                // Maneja la foto
                let foto = usuario.foto ? `<img src="${usuario.foto}" alt="Foto" width="50" height="50">` : 'Sin foto';
                
                // Maneja el rol
                let rol = usuario.rol;
                if (rol === "[]") {
                    rol = 'No asignado'; // Si el rol es un string vacío, asignamos "No asignado"
                } else if (typeof rol === 'string') {
                    try {
                        rol = JSON.parse(rol); // Si el rol es un string, intentamos convertirlo a array
                    } catch (e) {
                        rol = [rol]; // Si la conversión falla, lo tratamos como un array con un único valor
                    }
                }
                rol = Array.isArray(rol) && rol.length > 0 ? rol.join(', ') : 'No especificado';

                // Maneja el correo
                let correo = usuario.correo && usuario.correo.length > 0 ? usuario.correo : 'No especificado';

                // Añadir los datos a la tabla
                $('#usuarios-tbody').append(`
                    <tr id="usuario-${usuario.idUsuario}">
                        <td>${usuario.idUsuario}</td>
                        <td>
                            <span class="nombre">${usuario.nombre}</span>
                            <input class="form-control" type="text" name="nombre" value="${usuario.nombre}" style="display:none;">
                        </td>
                        <td>
                            <span class="correo">${correo}</span>
                            <input class="form-control" type="text" name="correo" value="${correo}" style="display:none;">
                        </td>
                        <td>
                            <span class="ubicacion">${usuario.ubicacion || 'No especificada'}</span>
                            <input class="form-control" type="text" name="ubicacion" value="${usuario.ubicacion || ''}" style="display:none;">
                        </td>
                        <td>
                            <span class="telefono">${usuario.telefono}</span>
                            <input class="form-control" type="text" name="telefono" value="${usuario.telefono}" style="display:none;">
                        </td>
                        <td>
                            <span class="rol">${rol}</span>
                            <input class="form-control" type="text" name="rol" value="${rol}" style="display:none;">
                        </td>
                        <td>
                            <span class="alergenos">${alergenos}</span>
                            <input class="form-control" type="text" name="alergenos" value="${alergenos}" style="display:none;">
                        </td>
                        <td class="foto">${foto}</td>
                        <td>
                            <button class="btn btn-warning btn-sm editar-btn" onclick="editarUsuario(${usuario.idUsuario})">Editar</button>
                            <button class="btn btn-success btn-sm guardar-btn" onclick="guardarUsuario(${usuario.idUsuario})" style="display:none;">Guardar</button>
                            <button class="btn btn-danger btn-sm eliminar-btn" onclick="eliminarUsuario(${usuario.idUsuario})">Eliminar</button>
                        </td>
                    </tr>
                `);
            });
        } else {
            alert('No se encontraron usuarios o error en la respuesta de la API.');
            console.log(response); // Verifica la respuesta completa para entender el problema
        }
    },
    error: function(xhr, status, error) {
        alert('Error al cargar los usuarios: ' + error);
    }
});



// Función para activar el modo de edición de un usuario
function editarUsuario(usuarioId) {
    $(`#usuario-${usuarioId} .form-control`).show();
    $(`#usuario-${usuarioId} .nombre, #usuario-${usuarioId} .correo, #usuario-${usuarioId} .ubicacion, #usuario-${usuarioId} .telefono, #usuario-${usuarioId} .rol, #usuario-${usuarioId} .alergenos`).hide();
    $(`#usuario-${usuarioId} .editar-btn`).hide();
    $(`#usuario-${usuarioId} .guardar-btn`).show();
}

// Función para guardar los cambios realizados en un usuario
function guardarUsuario(usuarioId) {
    const nombre = $(`#usuario-${usuarioId} input[name='nombre']`).val();
    const correo = $(`#usuario-${usuarioId} input[name='correo']`).val();
    const ubicacion = $(`#usuario-${usuarioId} input[name='ubicacion']`).val();
    const telefono = $(`#usuario-${usuarioId} input[name='telefono']`).val();
    const rol = $(`#usuario-${usuarioId} input[name='rol']`).val();
    const alergenos = $(`#usuario-${usuarioId} input[name='alergenos']`).val();

    const usuarioData = {
        idUsuario: usuarioId,
        nombre: nombre,
        correo: correo,
        ubicacion: ubicacion,
        telefono: telefono,
        rol: rol.split(','), // Convertir el texto en array
        alergenos: alergenos.split(',') // Convertir el texto en array
    };

    $.ajax({
        url: apiUrl,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(usuarioData),
        success: function(response) {
            if (response.success) {
                alert('Usuario actualizado correctamente.');
                cargarUsuarios(); // Recargar la lista
            } else {
                alert('Error al actualizar el usuario.');
            }
        },
        error: function() {
            alert('Error al realizar la solicitud.');
        }
    });
}

// Función para eliminar un usuario
function eliminarUsuario(usuarioId) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        $.ajax({
            url: apiUrl,
            method: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({ idUsuario: usuarioId }),
            success: function(response) {
                if (response.success) {
                    alert('Usuario eliminado correctamente.');
                    cargarUsuarios();
                } else {
                    alert('Error al eliminar el usuario.');
                }
            },
            error: function() {
                alert('Error al realizar la solicitud.');
            }
        });
    }
}

// Cargar los usuarios al cargar la página
$(document).ready(function() {
    cargarUsuarios();
});
