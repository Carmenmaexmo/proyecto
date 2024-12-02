<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebab Personalizado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/kebab-personalizado.css">
</head>
<body>
<main class="container mt-5" style="padding-top: 60px;" id="kebab-personalizado">
    <h1 class="text-center mb-4">Kebab Personalizado</h1>
    <div class="row">
        <!-- Columna de Ingredientes y Zona de Selección -->
        <div class="col-md-8">
            <!-- Imagen y descripción del kebab cargadas dinámicamente -->
            <img id="kebab-imagen" src="" alt="Kebab Personalizado" class="img-fluid rounded mb-4">
            
            <p id="kebab-descripcion" class="section-description text-center">
                <!-- La descripción se cargará desde la API -->
            </p>

            <!-- Ingredientes disponibles -->
            <div class="ingredients-grid mb-4" id="ingredients-container">
                <!-- Los ingredientes se cargarán dinámicamente aquí -->
            </div>
            
            <!-- Zona de selección de ingredientes -->
            <div class="drop-zone p-3 border rounded" ondrop="drop(event)" ondragover="allowDrop(event)">
                <h4 class="text-center mb-3">Zona de selección de ingredientes</h4>
                <div id="selected-ingredients" class="d-flex flex-wrap"></div>
            </div>
        </div>

        <!-- Columna de Detalles del Pedido -->
        <div class="col-md-4">
            <div class="order-section sticky-top mt-4" style="padding-top: 90px; padding-bottom: 90px;">
                <h2>Detalles del Pedido</h2>
                <form id="order-form" onsubmit="añadirKebabPersonalizadoAlCarrito(event)">
                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control" value="1" min="1" onchange="updatePrice()">
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio Total:</label>
                        <input type="text" id="precio" name="precio" class="form-control" value="0.00€" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 btn-block">Añadir al Carrito</button>
                </form>
            </div>
        </div>
    </div>
</main>
<script src="./js/kebab-personalizado.js"></script>
</body>
</html>
