<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Librería jQuery -->
    <title>Kebab de la Casa</title>
    <link rel="stylesheet" href="./css/kebab-lacasa.css">
</head>
<body>
    <main class="container mt-5" style="padding-top: 80px;" id="kebab-casa">
        <h1 class="text-center">Kebab de la Casa</h1>
        <div class="row">
            <div class="col-md-6">
                <!-- Imagen y descripción cargadas desde la BD -->
                <img id="kebab-imagen" src="" class="img-fluid" alt="Kebab de la Casa">
                <p id="kebab-descripcion" class="section-description mt-4"></p>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="details-box">
                            <h2>Detalles del Pedido</h2>
                            <form id="order-form">
                                <div class="form-group">
                                    <label for="cantidad">Cantidad:</label>
                                    <input type="number" id="cantidad" name="cantidad" class="form-control" value="1" min="1" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="precio">Precio unitario:</label>
                                    <p id="precio-unitario" class="form-control-static"></p>
                                </div>
                                <div class="form-group">
                                    <label for="precio-total">Precio total:</label>
                                    <p id="precio-total" class="form-control-static"></p>
                                </div>
                               <form id="add-to-cart-form" onsubmit="agregarAlCarrito(event)">
                                    <button type="submit">Añadir al carrito</button>
                                </form>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h2>Ingredientes</h2>
                <div id="ingredientes-list" class="row">
                    <!-- Los ingredientes específicos del kebab se cargarán aquí -->
                </div>
            </div>
        </div>
    </main>

    <script src="./js/kebab-lacasa.js"></script>
</body>
</html>
