// URL base para la API
const apiUrl = './Api/apiUser.php';  

// Función para cargar todos los usuarios
function cargarUsuarios() {
    $.ajax({
        url: apiUrl,  // Petición GET a la API para obtener todos los usuarios
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const usuarios = response.usuarios;
                $('#usuarios-tbody').html(''); // Limpiar la tabla antes de agregar los nuevos datos
                usuarios.forEach(usuario => {
                    let alergenos = usuario.alergenos ? usuario.alergenos.join(', ') : 'No especificados';
                    let foto = usuario.foto ? `<img src="${usuario.foto}" alt="Foto" width="50" height="50">` : 'Sin foto';
                    let rol = usuario.rol.length ? JSON.parse(usuario.rol).join(', ') : 'No asignado';

                    // Agregar cada usuario a la tabla
                    $('#usuarios-tbody').append(`
                        <tr id="usuario-${usuario.idUsuario}">
                            <td>${usuario.idUsuario}</td>
                            <td><span class="nombre">${usuario.nombre}</span><input class="form-control" type="text" value="${usuario.nombre}" style="display:none;"></td>
                            <td><span class="correo">${usuario.correo}</span><input class="form-control" type="text" value="${usuario.correo}" style="display:none;"></td>
                            <td><span class="ubicacion">${usuario.ubicacion}</span><input class="form-control" type="text" value="${usuario.ubicacion}" style="display:none;"></td>
                            <td><span class="telefono">${usuario.telefono}</span><input class="form-control" type="text" value="${usuario.telefono}" style="display:none;"></td>
                            <td><span class="rol">${rol}</span><input class="form-control" type="text" value="${rol}" style="display:none;"></td>
                            <td><span class="alergenos">${alergenos}</span><input class="form-control" type="text" value="${alergenos}" style="display:none;"></td>
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
                alert('Error al cargar los usuarios');
            }
        },
        error: function() {
            alert('Error al realizar la solicitud.');
        }
    });
}

// Función para activar el modo de edición de un usuario
function editarUsuario(usuarioId) {
    // Muestra el formulario de edición (inputs)
    $(`#usuario-${usuarioId} .form-control`).show();
    $(`#usuario-${usuarioId} .nombre, #usuario-${usuarioId} .correo, #usuario-${usuarioId} .ubicacion, #usuario-${usuarioId} .telefono, #usuario-${usuarioId} .rol, #usuario-${usuarioId} .alergenos`).hide();
    $(`#usuario-${usuarioId} .editar-btn`).hide();
    $(`#usuario-${usuarioId} .guardar-btn`).show();
}

// Función para guardar los cambios realizados en un usuario
function guardarUsuario(usuarioId) {
    const nombre = $(`#usuario-${usuarioId} .form-control[name='nombre']`).val();
    const correo = $(`#usuario-${usuarioId} .form-control[name='correo']`).val();
    const ubicacion = $(`#usuario-${usuarioId} .form-control[name='ubicacion']`).val();
    const telefono = $(`#usuario-${usuarioId} .form-control[name='telefono']`).val();
    const rol = $(`#usuario-${usuarioId} .form-control[name='rol']`).val();
    const alergenos = $(`#usuario-${usuarioId} .form-control[name='alergenos']`).val();

    const usuarioData = {
        idUsuario: usuarioId,
        nombre: nombre,
        correo: correo,
        ubicacion: ubicacion,
        telefono: telefono,
        rol: rol,
        alergenos: alergenos.split(',') // Convertir el texto de alergenos en un array
    };

    // Hacer la petición PUT para actualizar el usuario
    $.ajax({
        url: apiUrl,
        method: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(usuarioData),
        success: function(response) {
            if (response.success) {
                alert('Usuario actualizado correctamente');
                cargarUsuarios();  // Recargar la lista de usuarios
            } else {
                alert('Error al actualizar el usuario');
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
                    alert('Usuario eliminado correctamente');
                    cargarUsuarios();  // Recargar la lista de usuarios
                } else {
                    alert('Error al eliminar el usuario');
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
