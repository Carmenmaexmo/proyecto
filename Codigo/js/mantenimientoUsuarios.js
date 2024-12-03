// Cargar alérgenos y usuarios al cargar la página
$(document).ready(function() {
    cargarAlergenos(); // Primero cargamos los alérgenos
    cargarUsuarios(); // Luego cargamos los usuarios
});

// Función para cargar los alérgenos desde la API
function cargarAlergenos() {
    $.ajax({
        url: './api/ApiAlergenos.php', // URL para obtener los alérgenos
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log("Alérgenos cargados:", data);
            // Asegurarse de que window.alergenos sea un array válido
            window.alergenos = Array.isArray(data) ? data : [];
            cargarListaAlergenos(); // Cargar los alérgenos en la lista de checkboxes
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar los alérgenos:', error);
            // Asegurarse de que window.alergenos sea un array vacío en caso de error
            window.alergenos = [];
        }
    });
}

// Función para cargar los usuarios y mostrar los alérgenos asociados
function cargarUsuarios() {
    $.ajax({
        url: './Api/ApiUser.php', // URL para obtener los usuarios
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log("Usuarios cargados:", response);
            if (response.success && response.usuarios && Array.isArray(response.usuarios)) {
                const usuarios = response.usuarios;
                $('#usuarios-tbody').html(''); // Limpiar la tabla antes de agregar los nuevos datos

                usuarios.forEach(usuario => {
                    console.log(usuario); // Verifica los datos de cada usuario
                    
                    // Mostrar los alérgenos asociados al usuario
                    let alergenosUsuario = usuario.alergenos || [];
                    let alergenosHtml = '';

                    // Recorremos los alérgenos asociados al usuario y los mostramos en la tabla
                    alergenosUsuario.forEach(alergenoId => {
                        // Verificar que window.alergenos esté definido antes de buscar
                        if (Array.isArray(window.alergenos)) {
                            // Buscar el alérgeno en el listado de alérgenos previamente cargados
                            let alergeno = window.alergenos.find(a => a.idAlergenos === alergenoId);
                            if (alergeno) {
                                alergenosHtml += `
                                    <div class="alergeno-item" data-id="${alergeno.idAlergenos}">
                                        <img src="data:image/jpeg;base64,${alergeno.foto}" alt="${alergeno.nombre}" class="alergeno-img" style="width: 30px; height: auto; margin-right: 5px;">
                                        <span class="alergeno-nombre">${alergeno.nombre}</span>
                                    </div>
                                `;
                            }
                        }
                    });

                    // Si no tiene alérgenos, mostramos 'Ninguno'
                    if (alergenosHtml === '') {
                        alergenosHtml = 'Ninguno';
                    }

                    // Foto del usuario
                    let foto = usuario.foto ? `<img src="${usuario.foto}" alt="Foto de usuario" class="foto-usuario">` : 'Sin foto';
                    
                    // Mostrar el rol y el correo del usuario
                    let rol = usuario.rol;
                    if (rol === "[]") {
                        rol = 'No asignado';
                    } else if (typeof rol === 'string') {
                        try {
                            rol = JSON.parse(rol);
                        } catch (e) {
                            rol = [rol];
                        }
                    }
                    rol = Array.isArray(rol) && rol.length > 0 ? rol.join(', ') : 'No especificado';

                    let correo = usuario.correo && usuario.correo.length > 0 ? usuario.correo : 'No especificado';

                    // Añadir los datos a la tabla de usuarios
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
                                <span class="alergenos">${alergenosHtml}</span>
                            </td>
                            <td class="foto">${foto}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editar-btn" onclick="editarUsuario(${usuario.idUsuario})">Editar</button>
                                <button class="btn btn-success btn-sm guardar-btn" onclick="guardarUsuario(${usuario.idUsuario})" style="display:none;">Guardar</button>
                                <button class="btn btn-secondary btn-sm cancelar-btn" onclick="cancelarEdicion(${usuario.idUsuario})" style="display:none;">Cancelar</button>
                                <button class="btn btn-danger btn-sm eliminar-btn" onclick="eliminarUsuario(${usuario.idUsuario})">Eliminar</button>
                            </td>
                        </tr>
                    `);
                });
            } else {
                alert('No se encontraron usuarios o error en la respuesta de la API.');
                console.log(response);
            }
        },
        error: function(xhr, status, error) {
            alert('Error al cargar los usuarios: ' + error);
        }
    });
}

// Función para cargar los alérgenos en la lista de checkboxes
function cargarListaAlergenos() {
    if (Array.isArray(window.alergenos)) {
        const alergenosContainer = $('#alergenos-lista');
        alergenosContainer.html(''); // Limpiar la lista de alérgenos

        window.alergenos.forEach(alergeno => {
            alergenosContainer.append(`
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="${alergeno.idAlergenos}" id="alergeno-${alergeno.idAlergenos}">
                    <label class="form-check-label" for="alergeno-${alergeno.idAlergenos}">
                        <img src="data:image/jpeg;base64,${alergeno.foto}" alt="${alergeno.nombre}" class="alergeno-img" style="width: 30px; height: auto; margin-right: 5px;">
                        ${alergeno.nombre}
                    </label>
                </div>
            `);
        });
    }
}

// Función para habilitar los campos de edición del usuario
function editarUsuario(usuarioId) {
    let row = $(`#usuario-${usuarioId}`);
    
    // Mostrar campos de entrada
    row.find('.form-control').show();
    row.find('.nombre, .correo, .ubicacion, .telefono, .rol, .alergenos, .foto').prop('disabled', true);

    // Mostrar el formulario de alérgenos con los checkboxes
    let alergenosUsuario = row.find('.alergenos').data('alergenos') || [];
    window.alergenos.forEach(alergeno => {
        if (alergenosUsuario.includes(alergeno.idAlergenos)) {
            $(`#alergeno-${alergeno.idAlergenos}`).prop('checked', true);
        }
    });

    // Mostrar el input para subir foto
    row.find('.foto').html('<input type="file" name="foto" accept="image/*">');

    // Cambiar los botones
    row.find('.editar-btn').hide();
    row.find('.guardar-btn').show();
    row.find('.cancelar-btn').show();
}

// Función para guardar los cambios de usuario
function guardarUsuario(usuarioId) {
    let row = $(`#usuario-${usuarioId}`);
    let nombre = row.find('input[name="nombre"]').val();
    let correo = row.find('input[name="correo"]').val();
    let ubicacion = row.find('input[name="ubicacion"]').val();
    let telefono = row.find('input[name="telefono"]').val();
    let rol = row.find('input[name="rol"]').val();
    let foto = row.find('input[name="foto"]').prop('files')[0]; // Foto seleccionada por el usuario
    let alergenosSeleccionados = [];
    
    // Obtener alérgenos seleccionados
    $('#alergenos-lista input:checked').each(function() {
        alergenosSeleccionados.push($(this).val());
    });

    // Llamar a la API para actualizar el usuario
    $.ajax({
        url: './Api/ApiUser.php', // API para actualizar el usuario
        type: 'PUT',
        data: {
            id: usuarioId,
            nombre: nombre,
            correo: correo,
            ubicacion: ubicacion,
            telefono: telefono,
            rol: rol,
            alergenos: alergenosSeleccionados,
            foto: foto // Aquí deberías manejar la foto de alguna manera
        },
        success: function(response) {
            alert('Usuario actualizado');
            cargarUsuarios(); // Recargar los usuarios
        },
        error: function(xhr, status, error) {
            console.error('Error al actualizar el usuario:', error);
        }
    });
}

// Función para cancelar la edición de un usuario
function cancelarEdicion(usuarioId) {
    cargarUsuarios(); // Recargar la lista de usuarios
}


// Función para eliminar un usuario
function eliminarUsuario(usuarioId) {
    // Construir el objeto JSON con el idUsuario
    const datos = JSON.stringify({ idUsuario: usuarioId });

    $.ajax({
        url: './Api/ApiUser.php', // La URL de la API que gestionará la solicitud DELETE
        type: 'DELETE', // Método DELETE
        contentType: 'application/json', // Especificamos que el contenido es JSON
        data: datos, // Enviamos los datos del usuario en formato JSON
        success: function(response) {
            // Revisamos la respuesta del servidor
            const data = JSON.parse(response);
            if (data.success) {
                alert('Usuario eliminado correctamente.');
                // Aquí puedes actualizar la lista de usuarios o realizar alguna acción adicional
                cargarUsuarios(); // Si tienes una función para cargar la lista actualizada
            } else {
                alert('Error al eliminar el usuario: ' + data.error);
            }
        },
        error: function(xhr, status, error) {
            // Si ocurre un error, lo mostramos en consola o como un mensaje
            console.error('Error en la solicitud DELETE:', error);
            alert('Error al eliminar el usuario. Estado: ' + status + '. Error: ' + error);
        }
    });
}



