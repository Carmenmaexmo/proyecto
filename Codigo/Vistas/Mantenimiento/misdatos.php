<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Datos</title>
    <link rel="stylesheet" href="./css/misdatos.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> <!-- Para iconos -->
</head>
<body>
    <main>
        <div class="user-profile">
            <div class="profile-photo">
                <img id="user-photo" src="../imagenes/usuario.png" alt="Click para subir foto">
                <input type="file" id="photo-upload" accept="image/*" style="display: none;">
            </div>
            <h1 id="user-name">Nombre de Usuario</h1>
        </div>
        <div class="user-data">
            <div class="data-field">
                <strong>Correo:</strong> <span id="user-email"></span>
            </div>
            <div class="data-field">
                <strong>Teléfono:</strong> <span id="user-phone"></span>
            </div>
            <div class="data-field">
                <strong>Ubicación:</strong> <span id="user-location"></span>
            </div>
            <div class="data-field">
                <strong>Monedero:</strong> <span id="user-wallet"></span> 
            </div>
            <div class="data-field">
                <strong>Alérgenos:</strong>
                <ul id="allergen-list">
                    <!-- Aquí se cargarán los alérgenos seleccionados -->
                </ul>
            </div>
        </div>
        <div class="action-buttons">
            <button id="edit-button">Editar datos</button>
            <button id="save-button" style="display: none;">Guardar cambios</button>
        </div>
    </main>

    <script src="./js/misdatos.js"></script>
</body>
</html>
