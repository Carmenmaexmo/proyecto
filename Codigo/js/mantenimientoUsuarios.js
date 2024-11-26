// Función para cargar los alérgenos desde la API
function cargarAlergenos() {
    $.ajax({
        url: './api/ApiAlergenos.php', // URL para obtener los alérgenos
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log("Alérgenos cargados:", data);
            // Guardamos los alérgenos en una variable global o en un objeto para usarlos más tarde
            window.alergenos = data || []; // Guardamos los alérgenos
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar los alérgenos:', error);
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

// Cargar alérgenos y usuarios al cargar la página
$(document).ready(function() {
    cargarAlergenos(); // Primero cargamos los alérgenos
    cargarUsuarios(); // Luego cargamos los usuarios
});

// Función para habilitar los campos de edición del usuario
function editarUsuario(usuarioId) {
    let row = $(`#usuario-${usuarioId}`);
    
    // Mostrar campos de entrada
    row.find('.form-control').show();
    row.find('.nombre, .correo, .ubicacion, .telefono, .rol, .alergenos').hide();

    // Cambiar los botones
    row.find('.editar-btn').hide();
    row.find('.guardar-btn').show();
    row.find('.cancelar-btn').show();
}

// Función para guardar los cambios del usuario
function guardarUsuario(usuarioId) {
    let row = $(`#usuario-${usuarioId}`);

    // Obtener los nuevos valores de los campos editables
    let nombre = row.find('input[name="nombre"]').val();
    let ubicacion = row.find('input[name="ubicacion"]').val();
    let telefono = row.find('input[name="telefono"]').val();
    let contraseña = row.find('input[name="contraseña"]').val(); // Asumimos que este campo existe en el formulario
    let foto = row.find('input[name="foto"]').val(); // Asumimos que este campo existe en el formulario
    let rol = row.find('input[name="rol"]').val();

    // Verificación de campos obligatorios
    if (!nombre || !ubicacion || !telefono || !contraseña || !rol) {
        alert("Todos los campos son obligatorios.");
        return; // Si falta algún campo obligatorio, no se envía la solicitud
    }

    // Obtener los alérgenos asociados al usuario
    let alergenos = [];
    row.find('.alergenos .alergeno-item').each(function() {
        let alergenoId = $(this).data('id'); // Asegúrate de tener un atributo `data-id` en cada elemento de alergeno
        alergenos.push(alergenoId);
    });

    // Si no hay alérgenos seleccionados, enviar un array vacío
    if (alergenos.length === 0) {
        alergenos = [];
    }

    // Obtener el carrito (si es necesario)
    let carrito = row.find('input[name="carrito"]').val().split(','); // Suponemos que el carrito es un campo con valores separados por coma
    if (carrito.length === 0) {
        carrito = [];
    }

    // Suponemos un valor de monedero fijo o que el valor de monedero proviene de otro campo
    let monedero = row.find('input[name="monedero"]').val() || 0.00; // Si no se especifica, se asigna 0.00 como valor por defecto

    // Verificar que los datos sean correctos antes de enviar la solicitud
    console.log("Datos a enviar:", {
        idUsuario: usuarioId,
        nombre: nombre,
        ubicacion: ubicacion,
        telefono: telefono,
        contraseña: contraseña,
        foto: foto,
        monedero: monedero,
        carrito: carrito,
        rol: rol,
        alergenos: alergenos
    });

    // Realizar la llamada a la API para actualizar el usuario (usando PUT)
    $.ajax({
        url: './api/ApiUser.php', // URL para actualizar el usuario
        type: 'PUT', // Usamos PUT para la actualización
        contentType: 'application/json', // Asegúrate de enviar los datos como JSON
        dataType: 'json',
        data: JSON.stringify({
            idUsuario: usuarioId,
            nombre: nombre,
            ubicacion: ubicacion,
            telefono: telefono,
            contraseña: contraseña,  // La contraseña es obligatoria y editable
            foto: foto,  // La foto puede ser actualizada si es necesario
            monedero: monedero, // El valor de monedero, que por defecto es 0.00 si no se especifica
            carrito: carrito, // Enviamos el carrito como un array de productos
            rol: rol,  // El rol del usuario
            alergenos: alergenos // Enviamos los alérgenos como un array de IDs
        }),
        success: function(response) {
            console.log("Respuesta de la API:", response); // Agregar un console.log para ver la respuesta del servidor
            if (response.success) {
                // Actualizamos los valores en la tabla
                row.find('.nombre').text(nombre).show();
                row.find('.ubicacion').text(ubicacion).show();
                row.find('.telefono').text(telefono).show();
                row.find('.rol').text(rol).show();

                // Foto
                if (foto) {
                    row.find('.foto').html(`<img src="${foto}" alt="Foto de usuario" class="foto-usuario">`).show();
                }

                // Mostrar alérgenos
                row.find('.alergenos').html(alergenos.map(id => `<span>${id}</span>`).join(", ")).show();

                // Mostrar carrito (si es necesario)
                row.find('.carrito').html(carrito.join(', ')).show();

                // Ocultar los campos de edición
                row.find('.form-control').hide();

                // Cambiar los botones
                row.find('.guardar-btn').hide();
                row.find('.cancelar-btn').hide();
                row.find('.editar-btn').show();
            } else {
                alert('Error al guardar los cambios del usuario.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al guardar los cambios del usuario:', error);
            alert('Error al guardar los cambios del usuario: ' + error);
        }
    });
}

// Función para cancelar la edición
function cancelarEdicion(usuarioId) {
    let row = $(`#usuario-${usuarioId}`);
    
    // Volver a ocultar los campos de edición
    row.find('.form-control').hide();
    row.find('.nombre, .correo, .ubicacion, .telefono, .rol, .alergenos').show();

    // Cambiar los botones
    row.find('.guardar-btn').hide();
    row.find('.cancelar-btn').hide();
    row.find('.editar-btn').show();
}

// Función para eliminar un usuario
function eliminarUsuario(usuarioId) {
    if (confirm('¿Estás seguro de que quieres eliminar este usuario?')) {
        $.ajax({
            url: './api/ApiUser.php', // URL para eliminar el usuario
            type: 'DELETE',
            contentType: 'application/json', // Indicamos que el contenido es JSON
            dataType: 'json',
            data: JSON.stringify({
                idUsuario: usuarioId // Enviamos el ID como JSON en el cuerpo de la solicitud
            }),
            success: function(response) {
                if (response.success) {
                    $(`#usuario-${usuarioId}`).remove(); // Eliminar la fila de la tabla
                } else {
                    alert('Error al eliminar el usuario.');
                }
            },
            error: function(xhr, status, error) {
                alert('Error al eliminar el usuario: ' + error);
            }
        });
    }
}