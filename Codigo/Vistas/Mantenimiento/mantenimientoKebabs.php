<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Kebabs</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/mantenimiento.css"> 
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Mantenimiento de Kebabs</h1>

        <!-- Tabla para listar los kebabs existentes -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Foto</th>
                        <th>Precio (€)</th>
                        <th>Descripción</th>
                        <th>Ingredientes</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="kebabs-list">
                    <!-- Los datos serán generados dinámicamente -->
                </tbody>
            </table>
        </div>

        <!-- Formulario para añadir un nuevo kebab -->
        <!-- Formulario para añadir un nuevo kebab -->
        <div class="mt-5">
            <h2>Añadir Kebab</h2>
            <form id="form-nuevo-kebab" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre del Kebab</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto del Kebab</label>
                    <input type="file" class="form-control-file" id="foto" name="foto" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio (€)</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="ingredientes">Ingredientes (Selecciona uno o más)</label>
                    <select class="form-control" id="ingredientes" name="ingredientes[]" multiple required>
                        <!-- Opciones generadas dinámicamente -->
                    </select>
                    <small class="form-text text-muted">Usa Ctrl (Windows) o Cmd (Mac) para seleccionar múltiples ingredientes.</small>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Kebab</button>
            </form>
        </div>

    </div>

    <!-- Scripts -->
    <script>
       
    // Función para cargar los kebabs desde la API
    async function cargarKebabs() {
        const endpoint = './api/ApiKebab'; // Cambia a la URL de tu API
        try {
            const response = await fetch(endpoint, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Error al obtener los datos: ' + response.statusText);
            }

            const kebabs = await response.json();
            const tbody = document.getElementById('kebabs-list');

            // Limpiar la tabla antes de rellenarla
            tbody.innerHTML = '';

            // Rellenar la tabla con los datos de los kebabs
            kebabs.forEach(kebab => {
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td>${kebab.id}</td>
                    <td>${kebab.nombre}</td>
                    <td>
                        <img src="${kebab.foto}" alt="Foto de ${kebab.nombre}" style="width: 50px; height: auto;">
                    </td>
                    <td>${kebab.precio.toFixed(2)}</td>
                    <td>${kebab.descripcion}</td>
                    <td>${kebab.ingredientes ? kebab.ingredientes.join(', ') : 'N/A'}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editarKebab(${kebab.id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="borrarKebab(${kebab.id})">Borrar</button>
                    </td>
                `;
                tbody.appendChild(fila);
            });
        } catch (error) {
            console.error('Error:', error);
            alert('Error al cargar los kebabs.');
        }
    }

        // Función para editar un kebab
        function editarKebab(id) {
            alert('Editar Kebab ID: ' + id);
            // Aquí se puede redirigir a un formulario con los datos precargados
        }

        // Función para borrar un kebab
        function borrarKebab(id) {
            if (confirm('¿Estás seguro de que deseas borrar este kebab?')) {
                alert('Kebab ID ' + id + ' borrado.');
                // Aquí se debe implementar el código para borrar el kebab de la base de datos
            }
        }

        // Función para añadir un nuevo kebab (opcionalmente puedes manejar aquí el POST)
        document.getElementById('form-nuevo-kebab').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Nuevo kebab añadido.');
            // Aquí se debe implementar el código para añadir el kebab a la base de datos
        });

        // Llamar a la función para cargar los kebabs al cargar la página
        window.onload = cargarKebabs;
        window.onload = cargarKebabs;

        // Función para cargar ingredientes desde la API
async function cargarIngredientes() {
    const endpoint = 'http://localhost/api/ingredientes'; // Cambia la URL según sea necesario
    try {
        const response = await fetch(endpoint, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Error al obtener los ingredientes: ' + response.statusText);
        }

        const ingredientes = await response.json();
        const selectIngredientes = document.getElementById('ingredientes');

        // Limpiar opciones previas
        selectIngredientes.innerHTML = '';

        // Añadir opciones dinámicamente
        ingredientes.forEach(ingrediente => {
            const option = document.createElement('option');
            option.value = ingrediente.id; // ID del ingrediente
            option.textContent = ingrediente.nombre; // Nombre del ingrediente
            selectIngredientes.appendChild(option);
        });
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar los ingredientes.');
    }
}

// Llamar a la función para cargar los ingredientes al cargar la página
window.onload = function() {
    cargarKebabs(); // Cargar kebabs
    cargarIngredientes(); // Cargar ingredientes
};

    </script>
</body>
</html>
