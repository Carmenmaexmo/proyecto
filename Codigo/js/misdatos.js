document.addEventListener('DOMContentLoaded', function () {
    const idUsuario = localStorage.getItem('idUsuario');
    const userPhoto = document.getElementById('user-photo');
    const photoUpload = document.getElementById('photo-upload');
    const editButton = document.getElementById('edit-button');
    const saveButton = document.getElementById('save-button');
    const userDataFields = document.querySelectorAll('.user-data span');
    const allergenListContainer = document.getElementById('allergen-list'); // Contenedor para la lista de alérgenos

    let updatedData = {};  // Datos modificados por el usuario
    let originalData = {}; // Datos originales que no se modifican

    if (!idUsuario) {
        alert('No se ha encontrado el ID del usuario.');
        window.location.href = '?menu=login.php';  // Redirigir al login si no se encuentra el idUsuario
        return;
    }

    // Cargar los datos del usuario
    fetch(`./api/ApiUser.php?id=${idUsuario}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.usuario;

                // Mostrar los datos del usuario en el HTML
                document.getElementById('user-name').textContent = user.nombre;
                document.getElementById('user-email').textContent = user.correo;
                document.getElementById('user-phone').textContent = user.telefono;
                document.getElementById('user-location').textContent = user.ubicacion;
                document.getElementById('user-wallet').textContent = `${user.monedero} €`;

                // Mostrar los alérgenos del usuario
                const userAllergens = user.alergenos || [];
                const allergenListHTML = userAllergens.map(allergen => `<li>${allergen.nombre}</li>`).join('');
                allergenListContainer.innerHTML = allergenListHTML;

                // Guardar los datos originales
                originalData = {
                    nombre: user.nombre,
                    correo: user.correo,
                    telefono: user.telefono,
                    ubicacion: user.ubicacion,
                    monedero: user.monedero,
                    rol: user.rol,
                    carrito: user.carrito,
                    contraseña: user.contraseña,
                    foto: user.foto || null,
                    alergenos: userAllergens.map(allergen => allergen.idAlergenos),
                };

                // Mostrar la foto del usuario si tiene una
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

    // Subir una nueva foto
    userPhoto.addEventListener('click', () => {
        photoUpload.click();
    });

    photoUpload.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                userPhoto.src = e.target.result;
                updatedData['foto'] = file;
                saveButton.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        }
    });

    // Habilitar edición de los datos del usuario
    editButton.addEventListener('click', () => {
        userDataFields.forEach(field => {
            const key = field.id.split('-')[1]; // El id del campo (nombre, correo, etc.)
            
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

        // Cargar y editar los alérgenos
        fetch('./api/ApiAlergenos.php')
            .then(response => response.json())
            .then(allergenData => {
                const allergens = allergenData.alergenos || [];
                allergenListContainer.innerHTML = '';

                allergens.forEach(allergen => {
                    const isChecked = originalData.alergenos.includes(parseInt(allergen.idAlergenos));

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.id = `allergen-${allergen.idAlergenos}`;
                    checkbox.checked = isChecked;
                    checkbox.dataset.allergen = allergen.idAlergenos;

                    const label = document.createElement('label');
                    label.setAttribute('for', `allergen-${allergen.idAlergenos}`);
                    label.textContent = allergen.nombre;

                    const listItem = document.createElement('li');
                    listItem.appendChild(checkbox);
                    listItem.appendChild(label);
                    allergenListContainer.appendChild(listItem);

                    checkbox.addEventListener('change', () => {
                        const selectedAllergens = Array.from(document.querySelectorAll('[data-allergen]:checked'))
                            .map(el => parseInt(el.dataset.allergen));
                        updatedData['alergenos'] = selectedAllergens;
                        saveButton.style.display = 'inline-block';
                    });
                });
            })
            .catch(error => {
                console.error('Error al cargar los alérgenos:', error);
                alert('Hubo un error al cargar la lista de alérgenos.');
            });

        editButton.style.display = 'none';
        saveButton.style.display = 'inline-block';
    });

    // Guardar los cambios
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
            alergenos: updatedData.alergenos || originalData.alergenos,
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
