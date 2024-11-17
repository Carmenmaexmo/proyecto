// Validación del formulario
document.getElementById("registerForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevenimos el envío del formulario por defecto

    let valid = true;

    // Obtener valores
    const nombre = document.getElementById("nombre").value.trim();
    const telefono = document.getElementById("telefono").value.trim();
    const correo = document.getElementById("correo").value.trim();
    const contraseña = document.getElementById("contraseña").value.trim();
    const confirmarContraseña = document.getElementById("confirmarContraseña").value.trim();

    // Borrar mensajes previos
    document.getElementById("errorNombre").textContent = "";
    document.getElementById("errorTelefono").textContent = "";
    document.getElementById("errorCorreo").textContent = "";
    document.getElementById("errorContraseña").textContent = "";
    document.getElementById("errorConfirmarContraseña").textContent = "";

    // Validar nombre (mínimo 5 caracteres)
    if (nombre.length < 5) {
        console.log('Nombre inválido:', nombre.length);  // Depuración
        document.getElementById("errorNombre").textContent = "El nombre debe tener al menos 5 caracteres.";
        valid = false;
    }

    // Validar teléfono (9 dígitos)
    if (!/^[0-9]{9}$/.test(telefono)) {
        console.log('Teléfono inválido:', telefono);  // Depuración
        document.getElementById("errorTelefono").textContent = "El teléfono debe contener exactamente 9 dígitos.";
        valid = false;
    }

    // Validar correo (formato ----@----.---)
    const correoRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!correoRegex.test(correo)) {
        console.log('Correo inválido:', correo);  // Depuración
        document.getElementById("errorCorreo").textContent = "Por favor, ingrese un correo válido en el formato nombre@dominio.extensión.";
        valid = false;
    }

    // Validar contraseña
    if (contraseña.length < 8) {
        console.log('Contraseña inválida:', contraseña.length);  // Depuración
        document.getElementById("errorContraseña").textContent = "La contraseña debe tener al menos 8 caracteres.";
        valid = false;
    }

    // Validar confirmación de contraseña
    if (contraseña !== confirmarContraseña) {
        console.log('Contraseñas no coinciden:', contraseña, confirmarContraseña);  // Depuración
        document.getElementById("errorConfirmarContraseña").textContent = "Las contraseñas no coinciden.";
        valid = false;
    }

    // Si todo es válido, enviar los datos a la API
    if (valid) {
        // Recoger los datos del formulario
        const formData = {
            nombre: nombre,
            telefono: telefono,
            correo: correo,
            contraseña: contraseña,
            confirmarContraseña: confirmarContraseña,
            ubicacion: "Por determinar",  // Valor por defecto
            monedero: 0, // Inicializamos monedero en 0
            carrito: [], // Carrito vacío
            foto: null, // Foto opcional
            rol: "cliente", // Valor por defecto
            alergenos: [] // Alérgenos vacíos si no se proporcionan
        };
        
        fetch('./api/ApiUser.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            // Verificamos si la respuesta es JSON
            if (!response.ok) {
                throw new Error('Error al registrar el usuario: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Mostrar mensaje de éxito en un div
                const successMessage = document.getElementById("successMessage");
                successMessage.textContent = "¡Usuario creado correctamente!";
                successMessage.style.display = "block";
        
                // Vaciar los campos del formulario
                document.getElementById("registerForm").reset();
                
                // Ocultar el mensaje después de 5 segundos
                setTimeout(() => {
                    successMessage.style.display = "none";
                }, 5000); // 5000 ms = 5 segundos
            } else {
                alert("Error al registrar el usuario: " + data.error);
            }
        });
    }
});
