document.addEventListener('DOMContentLoaded', function () {
    const idUsuario = localStorage.getItem('idUsuario');
    const userPhoto = document.getElementById('user-photo');
    const photoUpload = document.getElementById('photo-upload');
    const editButton = document.getElementById('edit-button');
    const saveButton = document.getElementById('save-button');
    const userDataFields = document.querySelectorAll('.user-data span');
    const allergenListContainer = document.getElementById('allergen-list');
    const allergensContainer = document.createElement('div');
    allergensContainer.id = 'alergenos-container';
    let updatedData = {};
    let originalData = {};

    if (!idUsuario) {
        alert('No se ha encontrado el ID del usuario.');
        window.location.href = '?menu=login.php';
        return;
    }

    // Cargar los datos del usuario
    fetch(`./api/ApiUser.php?id=${idUsuario}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.usuario;

                // Mostrar datos del usuario
                document.getElementById('user-name').textContent = user.nombre;
                document.getElementById('user-email').textContent = user.correo;
                document.getElementById('user-phone').textContent = user.telefono;
                document.getElementById('user-location').textContent = user.ubicacion;
                document.getElementById('user-wallet').textContent = `${user.monedero} €`;

                // Alérgenos seleccionados por el usuario
                const userAllergens = user.alergenos || [];
                const allergenListHTML = userAllergens.map(allergen => `<li>${allergen.nombre}</li>`).join('');
                allergenListContainer.innerHTML = allergenListHTML;

                // Guardar datos originales
                originalData = {
                    ...user,
                    alergenos: userAllergens.map(allergen => allergen.idAlergenos),
                };

                if (user.foto) {
                    userPhoto.src = user.foto;
                }
            } else {
                alert('Error al cargar los datos del usuario.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al cargar los datos.');
        });

    // Función para cargar alérgenos desde la API usando jQuery
    function cargarAlergenos() {
        $.ajax({
            url: './api/ApiAlergenos.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                console.log("Datos de alérgenos recibidos:", data);
                let alergenos = data || []; // Asegurar que sea un array válido
                allergensContainer.innerHTML = ''; // Limpiar el contenedor antes de cargar nuevos datos

                alergenos.forEach(function (alergeno) {
                    // Crear checkbox y label para cada alérgeno
                    let isChecked = originalData.alergenos.includes(alergeno.idAlergenos);
                    let checkboxHTML = `
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                value="${alergeno.idAlergenos}" 
                                id="alergeno-${alergeno.idAlergenos}" 
                                name="alergenos[]" ${isChecked ? 'checked' : ''}>
                            <label class="form-check-label" for="alergeno-${alergeno.idAlergenos}">
                                ${alergeno.nombre}
                            </label>
                        </div>
                    `;
                    $(allergensContainer).append(checkboxHTML);
                });

                // Reemplazar la lista original con el contenedor editable
                $(allergenListContainer).replaceWith(allergensContainer);
            },
            error: function (xhr, status, error) {
                console.error('Error al cargar los alérgenos:', error);
                alert('Hubo un error al cargar la lista de alérgenos.');
            }
        });
    }

    // Editar datos
    editButton.addEventListener('click', () => {
        userDataFields.forEach(field => {
            const key = field.id.split('-')[1];

            if (key !== 'wallet') {
                const input = document.createElement('input');
                input.type = 'text';
                input.value = field.textContent.trim();
                input.dataset.key = key;
                field.replaceWith(input);

                input.addEventListener('input', () => {
                    updatedData[key] = input.value;
                    saveButton.style.display = 'inline-block';
                });
            }
        });

        // Cargar alérgenos al iniciar edición
        cargarAlergenos();
    });

    // Guardar cambios
    saveButton.addEventListener('click', () => {
        const formData = new FormData();
        formData.append('idUsuario', idUsuario);

        const allData = {
            nombre: updatedData.nombre || originalData.nombre,
            correo: updatedData.correo || originalData.correo,
            telefono: updatedData.telefono || originalData.telefono,
            ubicacion: updatedData.ubicacion || originalData.ubicacion,
            monedero: originalData.monedero,
            rol: originalData.rol,
            contraseña: originalData.contraseña,
            carrito: originalData.carrito,
            foto: updatedData.foto || originalData.foto,
        };

        for (const [key, value] of Object.entries(allData)) {
            if (key === 'alergenos' && Array.isArray(value)) {
                value.forEach(id => formData.append('alergenos[]', id));
            } else {
                formData.append(key, value);
            }
        }

        fetch('./api/ApiUser.php', {
            method: 'PUT',
            body: formData,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cambios guardados correctamente.');
                    location.reload();
                } else {
                    alert('Error al guardar los cambios.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al guardar los cambios.');
            });
    });
});
