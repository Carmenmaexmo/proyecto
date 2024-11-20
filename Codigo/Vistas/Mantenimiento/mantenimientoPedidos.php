<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Pedidos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5" style="padding-bottom: 50px;">
        <h1 class="text-center mb-4">Mantenimiento de Pedidos</h1>

        <!-- Tabla para listar pedidos existentes -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID Pedido</th>
                        <th>Estado</th>
                        <th>Fecha y Hora</th>
                        <th>Precio Total (€)</th>
                        <th>Usuario</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="pedidos-tbody">
                    <!-- Los datos se llenarán aquí usando AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Formulario para añadir o editar pedidos -->
        <div class="mt-5">
            <h2 id="form-title">Añadir Pedido</h2>
            <form id="form-pedido">
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control" id="estado" name="estado" required>
                        <option value="">Seleccione</option>
                        <option value="1">Pendiente</option>
                        <option value="2">Procesado</option>
                        <option value="3">Enviado</option>
                        <option value="4">Entregado</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="fechaHora">Fecha y Hora</label>
                    <input type="datetime-local" class="form-control" id="fechaHora" name="fechaHora" required>
                </div>
                <div class="form-group">
                    <label for="precioTotal">Precio Total (€)</label>
                    <input type="number" class="form-control" id="precioTotal" name="precioTotal" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <select class="form-control" id="usuario" name="usuario" required>
                        <!-- Opciones cargadas dinámicamente -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Pedido</button>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
       // Función para cargar los pedidos desde la API
        function cargarPedidos() {
            $.ajax({
                url: './api/ApiPedidos.php', 
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let tbody = $('#pedidos-tbody');
                    tbody.empty();

                    // Verifica si la respuesta contiene una lista de pedidos
                    if (!data || data.length === 0) {
                        tbody.append('<tr><td colspan="6" class="text-center">No hay pedidos disponibles.</td></tr>');
                    } else {
                        data.forEach(pedido => {
                            // Verificar si cada pedido tiene los datos esperados
                            const usuarioNombre = pedido.usuario && pedido.usuario.nombre ? pedido.usuario.nombre : 'Desconocido';

                            tbody.append(`
                                <tr id="pedido-${pedido.idPedidos}">
                                    <td>${pedido.idPedidos}</td>
                                    <td>${pedido.estado}</td>
                                    <td>${pedido.fechaHora}</td>
                                    <td>${pedido.precioTotal}</td>
                                    <td>${usuarioNombre}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editarPedido(${pedido.idPedidos})">Editar</button>
                                        <button class="btn btn-danger btn-sm" onclick="borrarPedido(${pedido.idPedidos})">Borrar</button>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                },
                error: function() {
                    alert('Error al cargar los pedidos.');
                }
            });
        }


        // Función para cargar usuarios en el formulario
        function cargarUsuarios() {
            $.ajax({
                url: './api/ApiUser.php', // Asegúrate de que la URL sea correcta.
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Verificar si la respuesta tiene éxito y contiene usuarios
                    if (response.success && Array.isArray(response.usuarios)) {
                        let usuarioSelect = $('#usuario');
                        usuarioSelect.empty();
                        usuarioSelect.append('<option value="">Seleccione un usuario</option>');

                        response.usuarios.forEach(usuario => {
                            usuarioSelect.append(`<option value="${usuario.idUsuario}">${usuario.nombre}</option>`);
                        });
                    } else {
                        alert('No se pudieron cargar los usuarios. Verifique la respuesta de la API.');
                    }
                },
                error: function() {
                    alert('Error al cargar usuarios. Compruebe la conexión con el servidor.');
                }
            });
        }

        // Función para guardar o actualizar pedido
        $('#form-pedido').on('submit', function(e) {
            e.preventDefault();
            let pedidoId = $(this).data('id');
            let formData = {
                estado: $('#estado').val(),
                fechaHora: $('#fechaHora').val(),
                precioTotal: $('#precioTotal').val(),
                usuario: $('#usuario').val()
            };

            let method = pedidoId ? 'PUT' : 'POST';
            let url = './api/ApiPedidos.php' + (pedidoId ? `/${pedidoId}` : '');

            $.ajax({
                url: url,
                type: method,
                contentType: 'application/json',
                data: JSON.stringify(formData),
                success: function() {
                    alert('Pedido guardado correctamente');
                    cargarPedidos();
                    $('#form-pedido').data('id', null);
                    $('#form-pedido')[0].reset();
                },
                error: function() {
                    alert('Error al guardar pedido');
                }
            });
        });

        // Función para editar pedido
        function editarPedido(id) {
            $.ajax({
                url: `./api/ApiPedidos.php/${id}`,
                type: 'GET',
                dataType: 'json',
                success: function(pedido) {
                    $('#estado').val(pedido.estado);
                    $('#fechaHora').val(pedido.fechaHora);
                    $('#precioTotal').val(pedido.precioTotal);
                    $('#usuario').val(pedido.usuario.idUsuario);
                    $('#form-pedido').data('id', pedido.idPedidos);
                    $('#form-title').text('Editar Pedido');
                },
                error: function() {
                    alert('Error al cargar datos del pedido');
                }
            });
        }

        // Función para borrar pedido
        function borrarPedido(id) {
            if (!confirm('¿Está seguro de eliminar este pedido?')) return;

            $.ajax({
                url: `./api/ApiPedidos.php/${id}`,
                type: 'DELETE',
                success: function() {
                    alert('Pedido eliminado correctamente');
                    cargarPedidos();
                },
                error: function() {
                    alert('Error al eliminar pedido');
                }
            });
        }

        $(document).ready(function() {
            cargarPedidos();
            cargarUsuarios();
        });
    </script>
</body>
</html>
