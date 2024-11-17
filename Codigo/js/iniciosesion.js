document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('login-form');
    const errorMessage = document.getElementById('error-message');

    form.addEventListener('submit', function (event) {
        event.preventDefault();  // Prevenir el envío del formulario de la forma tradicional

        // Obtener los datos ingresados en el formulario
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;

        // Verificar que los campos no estén vacíos
        if (!username || !password) {
            alert("Por favor, completa todos los campos.");
            return;
        }

        // Realizar una solicitud GET a la API para obtener todos los usuarios
        fetch('./api/ApiUser.php')  // Reemplaza con la URL de tu API
            .then(response => response.json())  // Convertir la respuesta a JSON
            .then(data => {
                console.log("Datos de la API:", data);

                // Variable para saber si encontramos un usuario válido
                let userFound = false;
                let userRole = '';

                // Recorrer todos los usuarios para verificar si existe un usuario con ese nombre y contraseña
                data.usuarios.forEach(user => {
                    if (user.nombre === username && user.contraseña === password) {
                        userFound = true;
                        userRole = user.rol;  // Obtener el rol del usuario encontrado
                    }
                });

                // Si encontramos un usuario y las credenciales son correctas
                if (userFound) {
                    localStorage.setItem('usuario', username);
                    localStorage.setItem('rol', userRole);

                    // Dependiendo del rol, redirigir a la página correspondiente
                    if (userRole === 'cliente') {
                        // Redirigir a la página de cliente
                        window.location.href = 'inicio.php'; // Aquí va la URL o ruta para los clientes
                    } else if (userRole === 'administrador') {
                        // Redirigir a la página de administrador
                        window.location.href = 'inicio.php'; // Aquí va la URL o ruta para los administradores
                    }
                } else {
                    // Si no se encuentra el usuario o las credenciales son incorrectas, mostrar el mensaje de error
                    errorMessage.style.display = 'block';  // Mostrar el mensaje de error
                }
            })
            .catch(error => {
                console.error('Error al obtener los usuarios:', error);
                alert('Hubo un error al procesar tu solicitud.');
            });
    });
});
