<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navegación</title>
    <style>
          /* Estilos para que el carrito se desplace si es necesario */
          #cart-items {
            max-height: 300px;  /* Ajusta la altura máxima según tus necesidades */
            overflow-y: auto;   /* Permite el desplazamiento vertical */
            padding: 10px;
            border: 1px solid #ccc;
            background-color: white;
            border-radius: 5px;
        }

        /* Agrega algunos estilos adicionales para mejorar la apariencia del carrito */
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .cart-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            margin-right: 10px;
        }

        .cart-item .cart-details {
            flex-grow: 1;
        }

        .cart-item button {
            background: none;
            border: none;
            color: red;
            cursor: pointer;
            font-size: 20px;
        }

        /* Estilo para el contador del carrito */
        #cart-count {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 0.2em 0.5em;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        /* Botones de aumentar y disminuir */
        .quantity-buttons {
            display: flex;
            align-items: center;
        }

        .quantity-buttons button {
            padding: 5px 10px;
            font-size: 18px;
            margin: 0 5px;
            cursor: pointer;
        }

        /* Estilo del precio total */
        .total-price {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            text-align: right;
        }
         /* Estilo para la ventana emergente */
        .popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
    </style>
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
                            <a class="dropdown-item" href="?menu=mantenimiento-usuarios">Mantenimiento Usuarios</a>
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
                    <div id="cartera-mostrar">
                        <a href="?menu=cartera" class="icon" title="Cartera">
                            <img src="../imagenes/monedero.png" alt="Cartera">
                        </a>
                    </div>
                    <div class="dropdown">
                    <a href="#" class="icon dropdown-toggle" id="cartDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Carrito">
                        <img src="../imagenes/carrito.png" alt="Carrito">
                        <span id="cart-count" class="badge badge-pill badge-danger" style="display: none;">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="cartDropdown">
                        <div id="cart-items">
                            <p class="text-center">El carrito está vacío.</p>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div id="total-price" class="total-price"></div>
                        <button id="checkout-btn" class="btn btn-primary btn-block" style="display: none;">Finalizar compra</button>
                    </div>
                    </div>
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
                 <!-- Ventana emergente -->
                <div id="login-popup" class="popup" style="display: none;">
                    <div class="popup-content">
                        <p>Debe iniciar sesión para finalizar la compra.</p>
                        <a href="?menu=login" class="btn btn-primary">Iniciar sesión aquí</a>
                        <button class="btn btn-secondary" id="close-popup-btn">Cerrar</button>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <script src="./js/header.js"></script></script>
</body>
</html>
