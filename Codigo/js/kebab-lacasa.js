document.addEventListener("DOMContentLoaded", () => {
    const apiUrl = "./Api/ApiKebab.php?tipo=kebab_casa"; // Cambia el puerto o ruta según sea necesario
    const kebabsContainer = document.getElementById("kebabs-container");

    // Función para obtener los "Kebab de la Casa"
    const fetchKebabsDeLaCasa = async () => {
        try {
            const response = await fetch(apiUrl);
            if (!response.ok) {
                throw new Error(`Error: ${response.status}`);
            }

            const kebabs = await response.json();
            renderKebabs(kebabs);
        } catch (error) {
            console.error("Error al obtener los kebabs de la casa:", error);
            kebabsContainer.innerHTML = `<p>Error al cargar los Kebabs de la Casa. Inténtalo más tarde.</p>`;
        }
    };

    // Función para renderizar los kebabs en el DOM

const renderKebabs = (kebabs) => {
    kebabsContainer.innerHTML = ""; // Limpiar el contenedor

    if (kebabs.length === 0) {
        kebabsContainer.innerHTML = `<p>No se encontraron Kebabs de la Casa.</p>`;
        return;
    }

    kebabs.forEach((kebab) => {
        const kebabCard = document.createElement("div");
        kebabCard.className = "kebab-card";

        // Verifica si la propiedad "foto" tiene una cadena Base64 válida
        let fotoUrl = kebab.foto || 'placeholder.jpg'; // Usa una imagen de marcador de posición si no hay foto
        if (fotoUrl.startsWith('/9j')) {  // Si comienza con el marcador de imagen JPEG Base64
            fotoUrl = 'data:image/jpeg;base64,' + fotoUrl;  // Agregar el prefijo adecuado para Base64
        }

        // Crear el HTML para la tarjeta de Kebab
        kebabCard.innerHTML = `
            <img src="${fotoUrl}" alt="${kebab.nombre}" class="kebab-image">
            <h3>${kebab.nombre}</h3>
            <p>${kebab.descripcion}</p>
            <span>${kebab.precio.toFixed(2)} €</span>
            
            <!-- Mostrar los ingredientes con imágenes -->
            <h4>Ingredientes:</h4>
            <ul>
                ${kebab.ingredientes
                    .map((ingrediente) => `
                        <li>
                            <img src="data:image/jpeg;base64,${ingrediente.foto}" alt="${ingrediente.nombre}" class="ingredient-image">
                            ${ingrediente.nombre}
                        </li>`)
                    .join("")}
            </ul>

            <!-- Campo para cantidad -->
            <div>
                <button class="quantity-btn" data-action="decrease">-</button>
                <input type="number" class="quantity-input" value="1" min="1">
                <button class="quantity-btn" data-action="increase">+</button>
            </div>
            
            <!-- Botón para añadir al carrito -->
            <button class="add-to-cart-btn" data-id="${kebab.id}">Añadir al carrito</button>
        `;

        // Añadir la tarjeta al contenedor
        kebabsContainer.appendChild(kebabCard);

        // Obtener los botones y el campo de cantidad
        const quantityInput = kebabCard.querySelector('.quantity-input');
        const increaseBtn = kebabCard.querySelector('.quantity-btn[data-action="increase"]');
        const decreaseBtn = kebabCard.querySelector('.quantity-btn[data-action="decrease"]');
        const addToCartBtn = kebabCard.querySelector('.add-to-cart-btn');

        // Funcionalidad para cambiar la cantidad
        increaseBtn.addEventListener('click', () => {
            quantityInput.value = parseInt(quantityInput.value) + 1;
        });

        decreaseBtn.addEventListener('click', () => {
            if (parseInt(quantityInput.value) > 1) {
                quantityInput.value = parseInt(quantityInput.value) - 1;
            }
        });

        // Funcionalidad para añadir al carrito
        addToCartBtn.addEventListener('click', () => {
            const cantidad = parseInt(quantityInput.value) || 1; // Obtén la cantidad del input
        
            // Verificar que el 'kebab' tiene un ID válido
            if (!kebab.idKebab) {
                console.error("El kebab no tiene un ID válido:", kebab);
                return;
            }
        
            // Crear una copia del objeto kebab para evitar que se compartan referencias
            const kebabCarrito = {
                id: kebab.idKebab, 
                nombre: kebab.nombre,
                precio: kebab.precio,
                cantidad: cantidad,
                foto: fotoUrl.split(',')[1], // Imagen en formato Base64
            };
        
            console.log("Añadiendo al carrito:", kebabCarrito);
        
            // Llamar a la función para añadir al carrito
            agregarAlCarrito(kebabCarrito, cantidad);
        });
        
        
    });
};

    // Llamar a la función para cargar los Kebabs de la Casa al cargar la página
    fetchKebabsDeLaCasa();
});


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



