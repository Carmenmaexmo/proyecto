<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Pedidos</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/mantenimiento.css">
</head>
<body style="padding-top: 80px;">
    <div class="container mt-5">
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
    <script src="./js/mantenimientoPedidos.js"></script>
</body>
</html>
