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
