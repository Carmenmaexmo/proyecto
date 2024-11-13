<?php
if (isset($_GET['menu'])) {
    if ($_GET['menu'] == "iniciar") {
        require_once 'index.php';
    }
    if ($_GET['menu'] == "inicio") {
        require_once './Vistas/Mantenimiento/inicio.php';
    }
    if ($_GET['menu'] == "kebab-casa") {
        require_once './Vistas/Mantenimiento/kebab-casa.php';
    }
    if ($_GET['menu'] == "kebab-personalizado") {
        require_once './Vistas/Mantenimiento/kebab-personalizado.php';
    }
    if ($_GET['menu'] == "contacto") {
        require_once './Vistas/Mantenimiento/contacto.php';
    }
    if ($_GET['menu'] == "cartera") {
        require_once './Vistas/Mantenimiento/cartera.php';
    }
    if ($_GET['menu'] == "carrito") {
        require_once './Vistas/Mantenimiento/carrito.php';
    }
    if ($_GET['menu'] == "login") {
        require_once './Vistas/Login/autentifica.php';
    }
    if ($_GET['menu'] == "cerrarsesion") {
        require_once './Vistas/Login/cerrarsesion.php';
     
    }
    if ($_GET['menu'] == "mantenimiento") {
        require_once './Vistas/mantenimiento/mantenimiento.php';
     
    }
    if ($_GET['menu'] == "listadoanimales") {
        require_once './Vistas/Mantenimiento/listadoanimales.php';
     
    }
    if ($_GET['menu'] == "listadovacunas") {
        require_once './Vistas/Mantenimiento/listadovacunas.php';
     
    }

    

    
}

    
    //Añadir otras rutas
