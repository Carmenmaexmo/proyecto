document.addEventListener('DOMContentLoaded', function () {
    // Seleccionar el enlace de "Cerrar Sesión"
    const logoutLink = document.querySelector('.dropdown-item[href="?menu=cerrar-sesion"]');

    if (logoutLink) {
        logoutLink.addEventListener('click', function (event) {
            event.preventDefault(); // Evitar comportamiento por defecto del enlace

            // Limpiar localStorage
            localStorage.removeItem('usuario');
            localStorage.removeItem('rol');

            console.log('Datos eliminados de localStorage: usuario y rol'); // Depuración

            // Redirigir al login
            window.location.href = '../Codigo/index.php';
        });
    } else {
        console.error('Enlace de "Cerrar Sesión" no encontrado');
    }
});
