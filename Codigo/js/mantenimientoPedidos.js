// Mapeo de números a palabras para el estado
const estados = {
    '1': 'Pendiente',
    '2': 'Procesado',
    '3': 'Enviado',
    '4': 'Entregado'
};

// Función para cargar los pedidos desde la API
function cargarPedidos() {
    $.ajax({
        url: './api/ApiPedidos.php', // URL de tu API
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let tbody = $('#pedidos-tbody');
            tbody.empty();

            if (!data || data.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center">No hay pedidos disponibles.</td></tr>');
            } else {
                if (!usuarios || !Array.isArray(usuarios)) {
                    alert('Error: La lista de usuarios no está disponible.');
                    return;
                }

                data.forEach(pedido => {
                    // Buscar el nombre del usuario con el ID del usuario
                    let usuario = usuarios.find(u => u.idUsuario == pedido.Usuario_idUsuario);
                    let usuarioNombre = usuario ? usuario.nombre : 'Desconocido';

                    // Mapear el estado a la palabra correspondiente
                    let estadoTexto = estados[pedido.estado] || 'Desconocido'; // Usamos 'Desconocido' como valor por defecto

                    tbody.append(`
                        <tr id="pedido-${pedido.idPedidos}" data-usuario-id="${pedido.Usuario_idUsuario}">
                            <td>${pedido.idPedidos}</td>
                            <td class="estado">${estadoTexto}</td> <!-- Muestra el texto del estado -->
                            <td class="fechaHora">${pedido.fechaHora}</td>
                            <td class="precioTotal">${pedido.precioTotal}</td>
                            <td>${usuarioNombre}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editar" data-id="${pedido.idPedidos}">Editar</button>
                                <button class="btn btn-success btn-sm guardar" data-id="${pedido.idPedidos}" style="display: none;">Guardar</button>
                                <button class="btn btn-secondary btn-sm cancelar" data-id="${pedido.idPedidos}" style="display: none;">Cancelar</button>
                                <button class="btn btn-danger btn-sm borrar" data-id="${pedido.idPedidos}">Borrar</button>
                            </td>
                        </tr>
                    `);
                });

                asignarEventos();
            }
        },
        error: function() {
            alert('Error al cargar los pedidos.');
        }
    });
}
    
    // Función para asignar eventos a los botones
    function asignarEventos() {
        // Botón Editar
        $('.editar').on('click', function() {
            let row = $(this).closest('tr');
            row.addClass('editando');
            row.find('.estado, .fechaHora, .precioTotal').each(function() {
                let valor = $(this).text().trim();
                let tipo = $(this).hasClass('estado') ? 'select' : 'input'; // Seleccionar tipo de campo editable
                let campo;

                if (tipo === 'select') {
                    campo = `
                        <select class="form-control">
                            <option value="1" ${valor == '1' ? 'selected' : ''}>Pendiente</option>
                            <option value="2" ${valor == '2' ? 'selected' : ''}>Procesado</option>
                            <option value="3" ${valor == '3' ? 'selected' : ''}>Enviado</option>
                            <option value="4" ${valor == '4' ? 'selected' : ''}>Entregado</option>
                        </select>`;
                } else {
                    campo = `<input class="form-control" type="text" value="${valor}">`;
                }

                $(this).html(campo);
            });

            row.find('.editar').hide();
            row.find('.guardar, .cancelar').show();
        });

    
        // Botón Guardar
        $('.guardar').on('click', function() {
            let row = $(this).closest('tr'); // Obtener la fila correspondiente
            let idPedido = $(this).data('id'); // Obtener el ID del pedido desde el botón

            // Obtener el ID del usuario directamente desde el atributo 'data-usuario-id' de la fila
            let usuarioId = row.data('usuario-id');

            if (!usuarioId) {
                alert("No se pudo encontrar el ID del usuario.");
                return;
            }

            // Datos editados
            let datosEditados = {
                estado: row.find('.estado select').val(),
                fechaHora: row.find('.fechaHora input').val(),
                precioTotal: row.find('.precioTotal input').val(),
                usuario: usuarioId // Enviar el ID del usuario directamente
            };

            // Enviar los datos actualizados a la API
            $.ajax({
                url: `./api/ApiPedidos.php/${idPedido}`, // URL de la API con el ID del pedido
                type: 'PUT',
                contentType: 'application/json',
                data: JSON.stringify(datosEditados), // Enviar los datos en formato JSON
                success: function() {
                    alert('Pedido actualizado correctamente.');
                     // Restaurar la fila a su estado no editable
                    row.removeClass('editando'); // Eliminar la clase de edición
                    row.find('.estado').html(row.find('.estado select').val() == '1' ? 'Pendiente' : 
                                            row.find('.estado select').val() == '2' ? 'Procesado' : 
                                            row.find('.estado select').val() == '3' ? 'Enviado' : 'Entregado');
                    row.find('.fechaHora').html(row.find('.fechaHora input').val());
                    row.find('.precioTotal').html(row.find('.precioTotal input').val());

                    // Ocultar los botones de guardar y cancelar, y mostrar el de editar
                    row.find('.guardar, .cancelar').hide();
                    row.find('.editar').show();
                },
                error: function(xhr, status, error) {
                    console.log('Error al actualizar:', error);
                    alert('Error al actualizar el pedido.');
                }
            });
        });

        // Botón Cancelar
        $('.cancelar').on('click', function() {
            let row = $(this).closest('tr');
            let idPedido = $(this).data('id');

            // Restaurar la fila a su estado original
            $.ajax({
                url: `./api/ApiPedidos.php/${idPedido}`,
                type: 'GET',
                dataType: 'json',
                success: function(pedido) {
                    row.find('.estado').html(pedido.estado);
                    row.find('.fechaHora').html(pedido.fechaHora);
                    row.find('.precioTotal').html(pedido.precioTotal);

                    row.removeClass('editando');
                    row.find('.guardar, .cancelar').hide();
                    row.find('.editar').show();
                },
                error: function() {
                    alert('Error al restaurar los datos del pedido.');
                }
            });
        });

        // Botón Borrar
        $('.borrar').on('click', function() {
            let idPedido = $(this).data('id');

            if (!confirm('¿Está seguro de eliminar este pedido?')) return;

            $.ajax({
                url: `./api/ApiPedidos.php/${idPedido}`, // URL de tu API
                type: 'DELETE',
                success: function() {
                    alert('Pedido eliminado correctamente.');
                    cargarPedidos();
                },
                error: function() {
                    alert('Error al eliminar pedido.');
                }
            });
        });
    }

    // Función para cargar usuarios en el formulario (en caso de que sea necesario)
    function cargarUsuarios(callback) {
        $.ajax({
            url: './api/ApiUser.php', // URL para obtener la lista de usuarios
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success && Array.isArray(response.usuarios)) {
                    usuarios = response.usuarios; // Guardar los usuarios en la variable global
    
                    // Rellenar el select en el formulario de "Añadir Pedido"
                    let usuarioSelect = $('#usuario');
                    usuarioSelect.empty();
                    usuarioSelect.append('<option value="">Seleccione un usuario</option>');
    
                    usuarios.forEach(usuario => {
                        usuarioSelect.append(`<option value="${usuario.idUsuario}">${usuario.nombre}</option>`);
                    });
    
                    if (callback) callback(); // Ejecutar el callback si existe
                } else {
                    alert('No se pudieron cargar los usuarios. Verifique la respuesta de la API.');
                }
            },
            error: function() {
                alert('Error al cargar usuarios. Compruebe la conexión con el servidor.');
            }
        });
    }

    $('#form-pedido').on('submit', function(e) {
        e.preventDefault(); // Prevenir el envío tradicional del formulario
    
        // Recoger los datos del formulario
        const nuevoPedido = {
            estado: $('#estado').val(),
            fechaHora: $('#fechaHora').val(),
            precioTotal: $('#precioTotal').val(),
            usuario: $('#usuario').val(), // Cambiar 'Usuario_idUsuario' a 'usuario'
        };
        
    
        // Convertir la fecha de 'YYYY-MM-DDTHH:MM' a 'YYYY-MM-DD HH:MM:SS'
        if (nuevoPedido.fechaHora) {
            nuevoPedido.fechaHora = nuevoPedido.fechaHora.replace('T', ' ') + ":00"; // Agregar segundos
        }
    
        console.log(nuevoPedido);
    
        // Validar que se ha seleccionado un usuario
        if (!nuevoPedido.usuario) {
            alert('Debe seleccionar un usuario.');
            return;
        }
    
        // Enviar los datos a la API para crear el nuevo pedido
        $.ajax({
            url: './Api/ApiPedidos.php', // URL para añadir el pedido
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(nuevoPedido),
            success: function(response) {
                alert('Pedido añadido correctamente.');
                $('#form-pedido')[0].reset(); // Limpiar el formulario
                cargarPedidos(); // Recargar la lista de pedidos
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Mostrar el error detallado
                alert('Error al añadir el pedido.');
            }
        });
    });
    
    

    $(document).ready(function() {
        cargarUsuarios(function() {
            cargarPedidos(); // Ejecutar después de cargar los usuarios
        });
    });
    

