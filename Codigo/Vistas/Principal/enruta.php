<?php
if (isset($_GET['menu'])) {
    if ($_GET['menu'] == "iniciar") {
        require_once 'index.php';
    } elseif ($_GET['menu'] == "inicio") {
        require_once './Vistas/Mantenimiento/inicio.php';
    } elseif ($_GET['menu'] == "kebab-casa") {
        require_once './Vistas/Mantenimiento/kebab-casa.php';
    } elseif ($_GET['menu'] == "kebab-personalizado") {
        require_once './Vistas/Mantenimiento/kebab-personalizado.php';
    } elseif ($_GET['menu'] == "contacto") {
        require_once './Vistas/Mantenimiento/contacto.php';
    } elseif ($_GET['menu'] == "cartera") {
        require_once './Vistas/Mantenimiento/cartera.php';
    } elseif ($_GET['menu'] == "carrito") {
        require_once './Vistas/Mantenimiento/carrito.php';
    } elseif ($_GET['menu'] == "login") {
        require_once './Vistas/Login/autentifica.php';
    } elseif ($_GET['menu'] == "registrarse") {
        require_once './Vistas/Login/registrarse.php';
    } elseif ($_GET['menu'] == "cerrarsesion") {
        require_once './Vistas/Login/cerrarsesion.php';
    } elseif ($_GET['menu'] == "mantenimiento-kebabs") {
        require_once './Vistas/Mantenimiento/mantenimientoKebabs.php';
    } elseif ($_GET['menu'] == "mantenimiento-ingredientes") {
        require_once './Vistas/Mantenimiento/mantenimientoIngredientes.php';
    } elseif ($_GET['menu'] == "mantenimiento-pedidos") {
        require_once './Vistas/Mantenimiento/mantenimientoPedidos.php' ; 
    } elseif ($_GET['menu'] == "listadovacunas") {
        require_once './Vistas/Mantenimiento/listadovacunas.php';
    } else {
        // Si el parámetro 'menu' no coincide con ninguna opción, se carga 'inicio.php'
        require_once './Vistas/Mantenimiento/inicio.php';
    }
} else {
    // Si no se pasa ningún parámetro 'menu', se carga 'inicio.php' por defecto
    require_once './Vistas/Mantenimiento/inicio.php';
}
?>
