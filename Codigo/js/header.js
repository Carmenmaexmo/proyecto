document.addEventListener('DOMContentLoaded', function () {
    const userDropdownMenu = document.getElementById('userDropdownMenu');
    const mantenimientoMenu = document.getElementById('mantenimientoMenu');
    
    const usuario = localStorage.getItem('usuario');
    const rol = localStorage.getItem('rol');

    // Mostrar/ocultar elementos según el rol y usuario
    userDropdownMenu.style.display = usuario && (rol === 'cliente' || rol === 'administrador') ? 'block' : 'none';
    mantenimientoMenu.style.display = rol === 'administrador' ? 'block' : 'none';
    document.querySelector('.btn-link').style.display = !usuario || !rol ? 'block' : 'none';

});

document.addEventListener('DOMContentLoaded', function () {
    // Cargar carrito inicial
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    actualizarContadorCarrito(carrito);  // Actualizar contador del carrito
    mostrarCarrito(carrito);  // Mostrar productos en el dropdown
});

// Función para agregar productos al carrito
function agregarAlCarrito(kebab, cantidad) {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    // Buscar si el producto ya está en el carrito
    const productoExistente = carrito.find(item => item.id === kebab.id);

    if (productoExistente) {
        // Si ya existe, solo actualizar la cantidad
        productoExistente.cantidad += cantidad;
    } else {
        // Si no existe, agregarlo como un nuevo producto
        carrito.push({
            id: kebab.id,
            nombre: kebab.nombre,
            cantidad: cantidad,
            precioUnitario: kebab.precio,
            imagen: kebab.foto,  // La cadena base64 de la imagen
        });
    }

    console.log("Carrito después de agregar:", carrito);

    // Guardar el carrito en localStorage
    localStorage.setItem('carrito', JSON.stringify(carrito));

    // Actualizar el contador visual y mostrar el carrito
    actualizarContadorCarrito(carrito);
    mostrarCarrito();

    alert(`${cantidad} "${kebab.nombre}" añadido(s) al carrito.`);
    console.log('Carrito desde localStorage:', JSON.parse(localStorage.getItem('carrito')));
}

// Función para mostrar el carrito de manera más ordenada
function mostrarCarrito() {
    const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const cartItems = document.getElementById('cart-items');
    const totalPriceContainer = document.getElementById('total-price');

    if (carrito.length === 0) {
        cartItems.innerHTML = '<p class="text-center">El carrito está vacío.</p>';
        totalPriceContainer.innerHTML = '';
        return;
    }

    // Ordenar el carrito y agregar un mejor diseño para el HTML
    let carritoHTML = carrito.map(item => `
        <div class="cart-item" style="display: flex; align-items: center; margin-bottom: 10px; padding: 10px; border-bottom: 1px solid #ccc;">
            <img src="data:image/jpeg;base64,${item.imagen}" alt="${item.nombre}" style="width: 60px; height: 60px; margin-right: 10px; object-fit: cover;">
            <div style="flex-grow: 1;">
                <strong>${item.nombre}</strong><br>
                <div class="quantity-buttons">
                    <button onclick="actualizarCantidad(${item.id}, -1, event)">-</button>
                    <span id="cantidad-${item.id}">${item.cantidad}</span>
                    <button onclick="actualizarCantidad(${item.id}, 1, event)">+</button>
                </div>
                Precio: ${(item.cantidad * item.precioUnitario).toFixed(2)} €
            </div>
            <!-- Botón de eliminación -->
            <button onclick="eliminarDelCarrito(${item.id})" style="background: none; border: none; color: red; cursor: pointer; font-size: 20px;">&#10060;</button>
        </div>
    `).join('');

    cartItems.innerHTML = carritoHTML;

    // Mostrar el botón de finalizar compra si el carrito no está vacío
    document.getElementById('checkout-btn').style.display = carrito.length > 0 ? 'block' : 'none';

    // Calcular el precio total
    const totalPrecio = carrito.reduce((total, item) => total + (item.cantidad * item.precioUnitario), 0);
    totalPriceContainer.innerHTML = `Precio Total: ${totalPrecio.toFixed(2)} €`;
}

// Función para actualizar la cantidad de un producto
function actualizarCantidad(id, delta, event) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
    const producto = carrito.find(item => item.id === id);

    if (!producto) return; // Si no se encuentra el producto, no hacer nada

    producto.cantidad += delta;

    // No permitir cantidades menores o iguales a cero
    if (producto.cantidad <= 0) {
        producto.cantidad = 1;
    }

    // Guardar el carrito actualizado en localStorage
    localStorage.setItem('carrito', JSON.stringify(carrito));

    // Evitar que el desplegable se cierre
    event.stopPropagation();

    // Actualizar la vista del carrito
    mostrarCarrito();
}

// Función para eliminar un producto del carrito
function eliminarDelCarrito(id) {
    let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

    // Filtrar el carrito para eliminar el producto con el ID especificado
    carrito = carrito.filter(item => item.id !== id);

    // Guardar el carrito actualizado en localStorage
    localStorage.setItem('carrito', JSON.stringify(carrito));

    // Actualizar la vista del carrito y el contador
    actualizarContadorCarrito(carrito);
    mostrarCarrito();
    alert('Producto eliminado del carrito');
}

// Función para actualizar el contador del carrito
function actualizarContadorCarrito(carrito) {
    const cartCount = document.getElementById('cart-count');
    const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);

    // Corregir el contador para que no se dupliquen
    cartCount.textContent = totalItems > 0 ? totalItems : '';
    cartCount.style.display = totalItems > 0 ? 'inline-block' : 'none';
}

// Función para mostrar el popup de login al finalizar
document.addEventListener('DOMContentLoaded', function () {
    const checkoutBtn = document.getElementById('checkout-btn');
    const loginPopup = document.getElementById('login-popup');
    const closePopupBtn = document.getElementById('close-popup-btn');

    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function () {
            const usuario = localStorage.getItem('usuario'); // Obtener usuario desde localStorage

            if (!usuario) {
                // Mostrar la ventana emergente si no hay usuario
                loginPopup.style.display = 'flex';
            } else {
                // Simular el proceso de compra
                alert('Compra en proceso...');

                // Vaciar el carrito (simulación de compra exitosa)
                localStorage.setItem('carrito', JSON.stringify([]));

                // Actualizar la vista del carrito
                actualizarContadorCarrito([]);
                mostrarCarrito();
            }
        });
    }

    if (closePopupBtn) {
        closePopupBtn.addEventListener('click', function () {
            loginPopup.style.display = 'none'; // Cerrar la ventana emergente
        });
    }
});

