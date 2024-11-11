<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/kebab-casa.css">
</head>
<body>
    <main class="container mt-5" style="padding-top: 80px;" id="kebab-casa">
        <h1 class="text-center">Kebab de la Casa</h1>
        <div class="row">
            <div class="col-md-6">
                <img src="../imagenes/kebab-lacasa.jpg" class="img-fluid" alt="Kebab de la Casa">
                <p class="section-description mt-4">
                Disfruta de nuestro kebab tradicional, lleno de sabor auténtico.
                </p>
                <div class="row">
                    <div class="col-md-6">
                    <h2>Detalles del Pedido</h2>
                <form id="order-form">
                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" id="cantidad" name="cantidad" class="form-control" value="1" min="1">
                    </div>

                    <div class="form-group">
                        <label>¿Tienes alguna alergia?</label><br>
                        <label>
                            <input type="radio" name="alergeno" value="no" checked> No
                        </label>
                        <label>
                            <input type="radio" name="alergeno" value="si"> Sí
                        </label>
                    </div>

                    <div id="alergeno-list" class="mt-2" style="display: none;">
                        <label>Alergenos:</label><br>
                        <label>
                            <input type="checkbox" name="alergenos[]" value="Gluten"> Gluten
                        </label><br>
                        <label>
                            <input type="checkbox" name="alergenos[]" value="Lactosa"> Lactosa
                        </label><br>
                        <label>
                            <input type="checkbox" name="alergenos[]" value="Frutos Secos"> Frutos Secos
                        </label><br>
                        <label>
                            <input type="checkbox" name="alergenos[]" value="Huevo"> Huevo
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Añadir al Carrito</button>
                </form>
                </div>
                </div>
            </div>
            
        
            <div class="col-md-6">
            <h2>Ingredientes</h2>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li>
                                <img src="../imagenes/pan.png" alt="pan" class="ingredient-image">
                                Pan
                            </li>
                            <li>
                                <img src="../imagenes/pollo.png" alt="pollo" class="ingredient-image">
                                Pollo
                            </li>
                            <li>
                                <img src="../imagenes/ternera.png" alt="ternera" class="ingredient-image">
                                Ternera
                            </li>
                            <li>
                                <img src="../imagenes/lechuga.png" alt="lechuga" class="ingredient-image">
                                Lechuga
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li>
                                <img src="../imagenes/tomate.png" alt="tomate" class="ingredient-image">
                                Tomate
                            </li>
                            <li>
                                <img src="../imagenes/cebolla.png" alt="cebolla" class="ingredient-image">
                                Cebolla
                            </li>
                            <li>
                                <img src="../imagenes/queso.png" alt="queso" class="ingredient-image">
                                Queso
                            </li>
                            <li>
                                <img src="../imagenes/salsa.png" alt="salsa" class="ingredient-image">
                                Salsa Especial
                            </li>
                        </ul>
                </div>
                </div>
            </div>
            </div>
        </div>
    </main>

    <script>
    // Script para mostrar u ocultar los alérgenos
    document.querySelectorAll('input[name="alergeno"]').forEach((elem) => {
        elem.addEventListener("change", function(event) {
            const alergenoList = document.getElementById('alergeno-list');
            if (event.target.value === "si") {
                alergenoList.style.display = 'block';
            } else {
                alergenoList.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>

