<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
                </ul>
                <div class="user-actions">
                    <a href="#cartera" class="icon" title="Cartera">
                        <img src="../imagenes/monedero.png" alt="Cartera">
                    </a>
                    <a href="#carrito" class="icon" title="Carrito">
                        <img src="../imagenes/carrito.png" alt="Carrito">
                        <span class="carrito-count">3</span> <!-- Número de artículos en el carrito -->
                    </a>
                    <div class="nav-item dropdown">
                        <a href="#profile" class="icon dropdown-toggle" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: black;">
                            <img src="../imagenes/usuario.png" alt="Usuario">
                        </a>
                        <div class="dropdown-menu" aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#datos">Mis datos</a>
                            <a class="dropdown-item" href="#historial">Historial Pedidos</a>
                            <a class="dropdown-item" href="#cerrar-sesion">Cerrar Sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
</body>
</html>
