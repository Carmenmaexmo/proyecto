<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegación</title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
            <div class="contenedor-logo">
                <img src="../imagenes/logo.png" alt="Logo" class="logo">
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="?menu=inicio">Inicio <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Carta
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="?menu=kebab-casa">Kebab de la Casa</a>
                            <a class="dropdown-item" href="?menu=kebab-personalizado">Kebab Personalizado</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?menu=contacto">Contacto</a>
                    </li>
                    <div class="nav-item dropdown" id="mantenimientoMenu" style="display: none;">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Mantenimiento
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="?menu=mantenimiento-kebabs">Mantenimiento Kebabs</a>
                            <a class="dropdown-item" href="?menu=mantenimiento-ingredientes">Mantenimiento Ingredientes y Alérgenos</a>
                            <a class="dropdown-item" href="?menu=mantenimiento-pedidos">Mantenimiento Pedidos</a>
                        </div>
                    </div>
                </ul>
                <div class="user-actions">
                    <div class="nav-item dropdown" id="userDropdownMenu" style="display: none;">
                        <a href="#profile" class="icon dropdown-toggle" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: black;">
                            <img src="../imagenes/usuario.png" alt="Usuario">
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="?menu=misdatos">Mis datos</a>
                            <a class="dropdown-item" href="?menu=historial">Historial Pedidos</a>
                            <a class="dropdown-item" href="?menu=cerrar-sesion">Cerrar Sesión</a> 
                        </div>
                    </div>
                    <a href="?menu=cartera" class="icon" title="Cartera">
                        <img src="../imagenes/monedero.png" alt="Cartera">
                    </a>
                    <div class="dropdown">
                        <a href="#" class="icon dropdown-toggle" id="cartDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Carrito">
                            <img src="../imagenes/carrito.png" alt="Carrito">
                            <span id="cart-count" class="badge badge-pill badge-danger" style="display: none;">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="cartDropdown">
                            <div id="cart-items">
                                <p class="text-center">El carrito está vacío.</p>
                            </div>
                            <div class="dropdown-divider"></div>
                            <button id="checkout-btn" class="btn btn-primary btn-block" style="display: none;">Finalizar compra</button>
                        </div>
                    </div>
                    <a href="?menu=login" class="btn btn-link" style="color: black; margin-left: 10px; text-decoration: underline; display: none;">Login</a>
                </div>
            </div>
        </nav>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            const mantenimientoMenu = document.getElementById('mantenimientoMenu');
            const cartCount = document.getElementById('cart-count');
            const cartItems = document.getElementById('cart-items');
            const checkoutBtn = document.getElementById('checkout-btn');
            
            const usuario = localStorage.getItem('usuario');
            const rol = localStorage.getItem('rol');

            // Mostrar/ocultar elementos según el rol y usuario
            userDropdownMenu.style.display = usuario && (rol === 'cliente' || rol === 'administrador') ? 'block' : 'none';
            mantenimientoMenu.style.display = rol === 'administrador' ? 'block' : 'none';
            document.querySelector('.btn-link').style.display = !usuario || !rol ? 'block' : 'none';

            // Función para cargar el carrito
            function cargarCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const cartCount = document.getElementById('cart-count');
            const cartItems = document.getElementById('cart-items');
            const checkoutBtn = document.getElementById('checkout-btn');

            cartItems.innerHTML = ''; // Limpiar el contenido del carrito
            cartCount.style.display = carrito.length > 0 ? 'inline' : 'none'; // Mostrar contador si hay productos en el carrito
            cartCount.textContent = carrito.reduce((acc, item) => acc + item.cantidad, 0); // Actualizar contador

            if (carrito.length === 0) {
                cartItems.innerHTML = '<p class="text-center">El carrito está vacío.</p>';
                checkoutBtn.style.display = 'none';
            } else {
                carrito.forEach(item => {
                    const productoHTML = `
                        <div style="display: flex; align-items: center; margin-bottom: 10px;">
                            <img src="${item.imagen}" alt="${item.nombre}" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                            <div>
                                <p style="margin: 0; font-weight: bold;">${item.nombre}</p>
                                <p style="margin: 0;">Cantidad: ${item.cantidad}</p>
                                <p style="margin: 0;">Total: ${item.precioTotal.toFixed(2)}€</p>
                            </div>
                        </div>
                    `;
                    cartItems.innerHTML += productoHTML;
                });
                checkoutBtn.style.display = 'block'; // Mostrar botón de finalizar compra
            }
        }
    });

        document.addEventListener('DOMContentLoaded', function() {
        // Llamar a cargarCarrito para cargar el estado inicial del carrito
        cargarCarrito();
        });


    </script>
</body>
</html>
