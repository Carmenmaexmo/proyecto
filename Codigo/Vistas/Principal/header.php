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
                     <!-- Apartado de Mantenimiento (solo visible para Administradores) -->
                     <div class="nav-item dropdown" id="mantenimientoMenu" style="display: none;">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Mantenimiento
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="?menu=mantenimiento-kebabs">Mantenimiento Kebabs</a>
                            <a class="dropdown-item" href="?menu=mantenimiento-ingredientes">Mantenimiento Ingredientes</a>
                            <a class="dropdown-item" href="?menu=mantenimiento-pedidos">Mantenimiento Pedidos</a>
                        </div>
                    </div>
                </ul>
                <div class="user-actions">
                     <!-- Apartado de Usuario (oculto si no hay usuario ni rol en localStorage) -->
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
                    <a href="?menu=carrito" class="icon" title="Carrito">
                        <img src="../imagenes/carrito.png" alt="Carrito">
                    </a>
                    <a href="?menu=login" class="btn btn-link" style="color: black; margin-left: 10px; text-decoration: underline; display: none;">Login</a>
                </div>
            </div>
        </nav>
    </header>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Leer el rol del usuario desde localStorage
            const usuario = localStorage.getItem('usuario');
            const rol = localStorage.getItem('rol');

            // Obtener los contenedores de los dropdowns
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            const mantenimientoMenu = document.getElementById('mantenimientoMenu');

            // Mostrar el dropdown de usuario solo si hay un usuario y es cliente o administrador
            if (usuario && (rol === 'cliente' || rol === 'administrador')) {
                userDropdownMenu.style.display = 'block';  // Mostrar el dropdown de usuario
            } else {
                userDropdownMenu.style.display = 'none';   // Ocultar el dropdown de usuario
            }

            // Mostrar el dropdown de usuario solo si hay un usuario y es cliente o administrador
            if (usuario && (rol === 'cliente' || rol === 'administrador')) {
                userDropdownMenu.style.display = 'block';  // Mostrar el dropdown de usuario
            } else {
                userDropdownMenu.style.display = 'none';   // Ocultar el dropdown de usuario
            }

            //Mostrar el enlace de login solo si no hay usuario o rol en localStorage
            if (!usuario || !rol) {
                document.querySelector('.btn-link').style.display = 'block';  // Mostrar el enlace de login
            } else {
                document.querySelector('.btn-link').style.display = 'none';   // Ocultar el enlace de login
            }

            // Mostrar el dropdown de mantenimiento solo si el rol es administrador
            if (rol === 'administrador') {
                mantenimientoMenu.style.display = 'block';  // Mostrar el dropdown de mantenimiento
            } else {
                mantenimientoMenu.style.display = 'none';   // Ocultar el dropdown de mantenimiento
            }

            // Manejar Cerrar Sesión
            const logoutLink = document.querySelector('.dropdown-item[href="?menu=cerrar-sesion"]');
            if (logoutLink) {
                logoutLink.addEventListener('click', function (event) {
                    event.preventDefault();  // Prevenir el comportamiento por defecto del enlace

                    // Limpiar el localStorage
                    localStorage.removeItem('usuario');
                    localStorage.removeItem('rol');
                    console.log('Datos eliminados de localStorage: usuario y rol');

                    // Redirigir al login
                    window.location.href = '?menu=login';
                });
            } else {
                console.error('Enlace de "Cerrar Sesión" no encontrado');
            }
        });
    </script>
    
</body>
</html>
