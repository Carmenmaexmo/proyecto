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
            <!-- Columna de Imagen, Descripción e Ingredientes -->
            <div class="col-md-6">
                <img src="../imagenes/kebab-personalizado.jpg" alt="Kebab Personalizado" class="img-fluid rounded mb-4">
                
                <p class="section-description text-center">
                    Elige y arrastra tus ingredientes favoritos para crear el kebab perfecto. Puedes añadir y quitar ingredientes arrastrándolos.
                </p>
                
                <div class="ingredients-grid">
                    <div class="ingredient-item" draggable="true" ondragstart="drag(event)" id="pollo">
                        <img src="../imagenes/pollo.png" alt="Pollo" class="ingredient-img">
                        <div>Pollo</div>
                    </div>
                    <div class="ingredient-item" draggable="true" ondragstart="drag(event)" id="ternera">
                        <img src="../imagenes/ternera.png" alt="Ternera" class="ingredient-img">
                        <div>Ternera</div>
                    </div>
                    <div class="ingredient-item" draggable="true" ondragstart="drag(event)" id="lechuga">
                        <img src="../imagenes/lechuga.png" alt="Lechuga" class="ingredient-img">
                        <div>Lechuga</div>
                    </div>
                    <div class="ingredient-item" draggable="true" ondragstart="drag(event)" id="tomate">
                        <img src="../imagenes/tomate.png" alt="Tomate" class="ingredient-img">
                        <div>Tomate</div>
                    </div>
                    <div class="ingredient-item" draggable="true" ondragstart="drag(event)" id="cebolla">
                        <img src="../imagenes/cebolla.png" alt="Cebolla" class="ingredient-img">
                        <div>Cebolla</div>
                    </div>
                    <div class="ingredient-item" draggable="true" ondragstart="drag(event)" id="queso">
                        <img src="../imagenes/queso.png" alt="Queso" class="ingredient-img">
                        <div>Queso</div>
                    </div>
                    <div class="ingredient-item" draggable="true" ondragstart="drag(event)" id="aceitunas">
                        <img src="../imagenes/aceitunas.png" alt="Aceitunas" class="ingredient-img">
                        <div>Aceitunas</div>
                    </div>
                    <div class="ingredient-item" draggable="true" ondragstart="drag(event)" id="remolacha">
                        <img src="../imagenes/remolacha.png" alt="Remolacha" class="ingredient-img">
                        <div>Remolacha</div>
                    </div>
                    <div class="ingredient-item" draggable="true" ondragstart="drag(event)" id="zanahoria">
                        <img src="../imagenes/zanahoria.png" alt="Zanahoria" class="ingredient-img">
                        <div>Zanahoria</div>
                    </div>
                </div>
                
                <div class="drop-zone" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <h4>Zona de selección de ingredientes</h4>
                    <div id="selected-ingredients"></div>
                </div>
            </div>

            <!-- Columna de Detalles del Pedido -->
            <div class="col-md-6">
                <div class="order-section mt-4">
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

                        <div id="alergeno-list" class="mt-2 hidden">
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
    </main>

    <script>
        // Permitir arrastre
        function allowDrop(event) {
            event.preventDefault();
        }

        // Inicio del arrastre
        function drag(event) {
            event.dataTransfer.setData("text", event.target.closest(".ingredient-item").id);
        }

        // Acción de soltar para añadir o quitar ingredientes
        function drop(event) {
            event.preventDefault();
            const data = event.dataTransfer.getData("text");
            const selectedIngredients = document.getElementById("selected-ingredients");

            // Verifica si el ingrediente ya está en el área de selección
            if (!document.getElementById(`selected-${data}`)) {
                const ingredient = document.getElementById(data).cloneNode(true);
                ingredient.id = `selected-${data}`;  // Cambia el ID para evitar duplicados
                ingredient.classList.add("ingredient-in-dropzone");
                ingredient.draggable = false; // Desactiva el arrastre dentro de la zona de selección
                ingredient.onclick = function() { removeIngredient(this.id); };
                selectedIngredients.appendChild(ingredient);
            }
        }

        // Eliminar ingrediente al hacer clic
        function removeIngredient(id) {
            const ingredient = document.getElementById(id);
            if (ingredient) {
                ingredient.remove();
            }
        }

        // Mostrar u ocultar alérgenos
        document.querySelectorAll('input[name="alergeno"]').forEach((elem) => {
            elem.addEventListener("change", function(event) {
                const alergenoList = document.getElementById('alergeno-list');
                alergenoList.style.display = event.target.value === "si" ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
