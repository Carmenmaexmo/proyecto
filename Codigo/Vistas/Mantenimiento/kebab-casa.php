<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/kebab-casa.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Librería jQuery -->
    <title>Kebab de la Casa</title>
    <style>
        /* Estilo para la sección de detalles del pedido */
        .details-box {
            background-color: #f9f9f9;
            border: 2px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        .details-box h2 {
            font-size: 1.5rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        /* Estilo para los inputs del formulario */
        input[type="number"] {
            padding: 10px;
            font-size: 1.1rem;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center; /* Centrar el número */
        }

        /* Asegurar que las flechas de incremento y decremento no se oculten */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none; /* Mostrar las flechas */
            margin: 0;
        }

        input[type="number"] {
            appearance: textfield; /* Para garantizar la apariencia adecuada */
        }

        /* Estilo para el botón */
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
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
                                    <input type="number" id="cantidad" name="cantidad" min="1" max="10" value="1"  class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="precio">Precio unitario:</label>
                                    <p id="precio-unitario" class="form-control-static"></p>
                                </div>
                                <div class="form-group">
                                    <label for="precio-total">Precio total:</label>
                                    <p id="precio-total" class="form-control-static"></p>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Añadir al Carrito</button>
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

    <script>
    // Función para cargar los datos del Kebab de la Casa y sus ingredientes
    function cargarKebabDeLaCasa() {
        $.ajax({
            url: './api/ApiKebab.php', // URL de la API de kebabs
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Buscar el "Kebab de la Casa" en los datos retornados
                let kebabDeLaCasa = data.find(kebab => kebab.nombre === "Kebab de la casa");

                if (kebabDeLaCasa) {
                    // Actualizar el precio unitario en el formulario
                    const precioUnitario = kebabDeLaCasa.precio;
                    document.getElementById('precio-unitario').textContent = precioUnitario.toFixed(2);

                    // Calcular el precio total basado en la cantidad
                    calcularPrecioTotal(precioUnitario);

                    // Cargar los ingredientes específicos del Kebab de la Casa
                    cargarIngredientes(kebabDeLaCasa.ingredientes); // Pasamos los ingredientes del kebab

                    // Actualizar la imagen y descripción del Kebab de la Casa
                    const imagenBase64 = kebabDeLaCasa.foto;
                    document.getElementById('kebab-imagen').src = `data:image/jpeg;base64,${imagenBase64}`;
                    document.getElementById('kebab-descripcion').textContent = kebabDeLaCasa.descripcion;
                } else {
                    alert('Error: No se encontró el Kebab de la Casa.');
                }
            },
            error: function() {
                alert('Error al cargar los datos del kebab.');
            }
        });
    }

    // Función para cargar los ingredientes específicos del kebab
    function cargarIngredientes(ingredientes) {
        const ingredientesContainer = $('#ingredientes-list');
        ingredientesContainer.empty(); // Limpiar el contenedor

        ingredientes.forEach(ingrediente => {
            const ingredienteHtml = `
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li>
                            <img src="data:image/jpeg;base64,${ingrediente.foto}" alt="${ingrediente.nombre}" class="ingredient-image">
                            ${ingrediente.nombre}
                        </li>
                    </ul>
                </div>`;
            ingredientesContainer.append(ingredienteHtml);
        });
    }

    // Función para calcular el precio total dinámicamente
    function calcularPrecioTotal(precioUnitario) {
        const cantidadInput = document.getElementById('cantidad');
        const precioTotalElement = document.getElementById('precio-total');

        // Escuchar cambios en la cantidad
        cantidadInput.addEventListener('input', () => {
            const cantidad = parseInt(cantidadInput.value) || 1;
            const precioTotal = precioUnitario * cantidad;
            precioTotalElement.textContent = precioTotal.toFixed(2);
        });

        // Calcular inicialmente con la cantidad por defecto
        const cantidadInicial = parseInt(cantidadInput.value) || 1;
        const precioTotalInicial = precioUnitario * cantidadInicial;
        precioTotalElement.textContent = precioTotalInicial.toFixed(2);
    }

    // Función para manejar el cambio en la pregunta de alergias
    function manejarCambioAlergia() {
        const alergiaSi = document.getElementById('alergia-si');
        const alergiaNo = document.getElementById('alergia-no');
        const alergenosSeleccionados = document.getElementById('alergenos-seleccionados');
        
        // Si el usuario selecciona "Sí", mostramos los alérgenos
        if (alergiaSi.checked) {
            alergenosSeleccionados.style.display = 'block';
            cargarAlergenos(1); // Pasamos el ID del Kebab de la Casa
        } else {
            // Si el usuario selecciona "No", ocultamos los alérgenos
            alergenosSeleccionados.style.display = 'none';
        }
    }

    // Llamar a las funciones al cargar la página
    document.addEventListener('DOMContentLoaded', () => {
        cargarKebabDeLaCasa(); // Llamar la función de carga del Kebab
    });

    // Llamar a las funciones al cargar la página
    document.getElementById('order-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevenir el comportamiento por defecto del formulario
    
    const nombreProducto = "Kebab de la Casa"; // Nombre del producto
    const cantidad = parseInt(document.getElementById('cantidad').value) || 1;
    const precioUnitario = parseFloat(document.getElementById('precio-unitario').textContent);
    const precioTotal = cantidad * precioUnitario;
    const imagenProducto = document.getElementById('kebab-imagen').src;

    // Crear un objeto para el producto
    const producto = {
        nombre: nombreProducto,
        cantidad: cantidad,
        precioUnitario: precioUnitario,
        precioTotal: precioTotal,
        imagen: imagenProducto
    };

    // Obtener el carrito del localStorage (si no existe, se crea uno vacío)
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    // Verificar si el producto ya está en el carrito
    const index = carrito.findIndex(item => item.nombre === nombreProducto);
    if (index !== -1) {
        // Si ya existe, actualizamos la cantidad y el precio total
        carrito[index].cantidad += cantidad;
        carrito[index].precioTotal = carrito[index].cantidad * carrito[index].precioUnitario;
    } else {
        // Si no existe, lo añadimos al carrito
        carrito.push(producto);
    }

    // Guardar el carrito actualizado en el localStorage
    localStorage.setItem('carrito', JSON.stringify(carrito));

    // Mostrar una notificación al usuario
    alert(`${nombreProducto} añadido al carrito.`);

    // Actualiza el contenido del carrito desplegable
    cargarCarrito();
});


    </script>
</body>
</html>
