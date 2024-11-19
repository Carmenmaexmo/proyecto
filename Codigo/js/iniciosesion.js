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
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al obtener los usuarios');
                }
                return response.json();
            })
            .then(data => {
                console.log("Datos de la API:", data);

                if (!data.success) {
                    throw new Error('Error en la respuesta de la API');
                }

                // Asegúrate de que los usuarios existen
                if (!data.usuarios) {
                    throw new Error('La propiedad "usuarios" no está presente en la respuesta');
                }

                let userFound = false;
                let userRole = '';

                // Recorrer todos los usuarios para verificar si existe un usuario con ese nombre y contraseña
                data.usuarios.forEach(user => {
                    // Asegúrate de que `rol` esté correctamente definido (no sea una cadena vacía)
                    if (user.nombre === username && user.contraseña === password) {
                        userFound = true;
                        userRole = user.rol;  // Aquí tomamos el rol directamente (debería ser 'cliente' o 'administrador')
                        userId = user.idUsuario;  // Guardar el idUsuario
                    }
                });

                // Si encontramos un usuario y las credenciales son correctas
                if (userFound) {
                    localStorage.setItem('usuario', username);
                    localStorage.setItem('rol', userRole);
                    localStorage.setItem('idUsuario', userId);
                    window.location.href = '../Codigo/index.php'; 
                } else {
                    // Si no se encuentra el usuario o las credenciales son incorrectas, mostrar el mensaje de error
                    errorMessage.style.display = 'block';  // Mostrar el mensaje de error
                }
            })
            .catch(error => {
                console.error('Error al obtener los usuarios:', error);
                errorMessage.style.display = 'block';
            });
    });
});
