let saldo = 0.00;
console.log(localStorage);

// Obtener el ID del usuario desde el localStorage
const userId = localStorage.getItem('idUsuario');

// Función para actualizar el saldo en la página
function actualizarSaldo() {
    document.getElementById("saldo").textContent = saldo.toFixed(2);
}

// Función para obtener el saldo desde la base de datos
function cargarSaldoDesdeBaseDeDatos() {
    fetch(`./Api/ApiUser.php?id=${userId}`, {  // Asegúrate de que el endpoint correcto esté aquí
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.usuario) {
            saldo = data.usuario.monedero;  // Asignar el saldo al valor del monedero
            actualizarSaldo();  // Actualizar el saldo en la página
        } else {
            console.error('Error al cargar el saldo:', data.error);
        }
    })
    .catch(error => console.error('Error en la solicitud:', error));
}

// Función para actualizar el saldo en la base de datos
function actualizarSaldoEnBaseDeDatos() {
    fetch('./Api/ApiUser.php?action=updateMonedero', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            idUsuario: userId,  // El ID del usuario que estás actualizando
            nuevoSaldo: saldo   // El saldo actualizado
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Monedero actualizado correctamente en la base de datos');
        } else {
            console.error('Error al actualizar el monedero:', data.error);
        }
    })
    .catch(error => console.error('Error en la solicitud:', error));
}

// Añadir dinero al monedero
document.getElementById("depositForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const amountToAdd = parseFloat(document.getElementById("amountDeposit").value);
    if (amountToAdd > 0) {
        saldo += amountToAdd;
        document.getElementById("message").textContent = `Se han añadido ${amountToAdd.toFixed(2)}€ al monedero.`;
        document.getElementById("message").style.color = "green";
        actualizarSaldo();
        // Actualizar el saldo en la base de datos
        actualizarSaldoEnBaseDeDatos();
    } else {
        document.getElementById("message").textContent = "Por favor, ingresa una cantidad válida para añadir.";
        document.getElementById("message").style.color = "red";
    }
    document.getElementById("amountDeposit").value = ''; // Limpiar campo
});

// Retirar dinero del monedero
document.getElementById("withdrawForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const amountToWithdraw = parseFloat(document.getElementById("amountWithdraw").value);
    if (amountToWithdraw > 0 && amountToWithdraw <= saldo) {
        saldo -= amountToWithdraw;
        document.getElementById("message").textContent = `Se han retirado ${amountToWithdraw.toFixed(2)}€ del monedero.`;
        document.getElementById("message").style.color = "green";
        actualizarSaldo();
        // Actualizar el saldo en la base de datos
        actualizarSaldoEnBaseDeDatos();
    } else if (amountToWithdraw > saldo) {
        document.getElementById("message").textContent = "No tienes suficiente saldo para retirar esta cantidad.";
        document.getElementById("message").style.color = "red";
    } else {
        document.getElementById("message").textContent = "Por favor, ingresa una cantidad válida para retirar.";
        document.getElementById("message").style.color = "red";
    }
    document.getElementById("amountWithdraw").value = ''; // Limpiar campo
});

// Inicialización de saldo al cargar la página
cargarSaldoDesdeBaseDeDatos();
