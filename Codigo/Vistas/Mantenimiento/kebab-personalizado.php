<main class="container mt-5" style="padding-top: 80px;" id="kebab-personalizado">
    <h1 class="text-center">Kebab Personalizado</h1>
        
    <div class="row">
        <!-- Columna de Imagen y Descripción -->
        <div class="col-md-6">
            <img src="../imagenes/kebab-personalizado.jpg" alt="Kebab Personalizado" class="img-fluid">
            
            <p class="section-description mt-4">
                Elige tus ingredientes para crear tu kebab a medida. Selecciona la cantidad y añade cualquier alergeno si es necesario.
            </p>
            
            <div class="ingredients-grid">
                <div class="ingredient-item">
                    <img src="../imagenes/pollo.png" alt="Pollo" class="ingredient-img">
                    <label>
                        <input type="checkbox" name="ingredientes[]" value="Pollo"> Pollo
                    </label>
                </div>
                <div class="ingredient-item">
                    <img src="../imagenes/ternera.png" alt="Ternera" class="ingredient-img">
                    <label>
                        <input type="checkbox" name="ingredientes[]" value="Ternera"> Ternera
                    </label>
                </div>
                <div class="ingredient-item">
                    <img src="../imagenes/vegetales.png" alt="Vegetales" class="ingredient-img">
                    <label>
                        <input type="checkbox" name="ingredientes[]" value="Vegetales"> Vegetales
                    </label>
                </div>
                <div class="ingredient-item">
                    <img src="../imagenes/queso.png" alt="Queso" class="ingredient-img">
                    <label>
                        <input type="checkbox" name="ingredientes[]" value="Queso"> Queso
                    </label>
                </div>
                <div class="ingredient-item">
                    <img src="../imagenes/salsa.png" alt="Salsa" class="ingredient-img">
                    <label>
                        <input type="checkbox" name="ingredientes[]" value="Salsa"> Salsa
                    </label>
                </div>
                <div class="ingredient-item">
                    <img src="../imagenes/aguacate.png" alt="Aguacate" class="ingredient-img">
                    <label>
                        <input type="checkbox" name="ingredientes[]" value="Aguacate"> Aguacate
                    </label>
                </div>
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
