<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Ingredientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5" style="padding-bottom: 50px; padding-top: 80px;">
        <h1 class="text-center mb-4">Mantenimiento de Ingredientes y Alérgenos</h1>

        <!-- Tabla para listar ingredientes existentes -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Foto</th>
                        <th>Precio (€)</th>
                        <th>Obligatorio</th>
                        <th>Alérgenos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="ingredientes-tbody">
                    <!-- Los datos se llenarán aquí usando AJAX -->
                </tbody>
            </table>
        </div>

        <!-- Formulario para añadir un nuevo ingrediente -->
        <div class="mt-5">
            <h2>Añadir Ingrediente</h2>
            <form id="form-nuevo-ingrediente" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombre">Nombre del Ingrediente</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto del Ingrediente</label>
                    <input type="file" class="form-control-file" id="foto" name="foto" required>
                </div>
                <div class="form-group">
                    <label for="precio">Precio (€)</label>
                    <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="obligatorio">¿Es obligatorio?</label>
                    <select class="form-control" id="obligatorio" name="obligatorio" required>
                        <option value="">Seleccione</option>
                        <option value="1">Sí</option>
                        <option value="0">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="alergenos">Alérgenos</label>
                    <div id="alergenos-container">
                        <!-- Los alérgenos se cargarán aquí -->
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Ingrediente</button>
            </form>
        </div>
        <!-- Tabla para listar alérgenos existentes -->
        <div class="mt-5">
            <h2>Lista de Alérgenos</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Foto</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="alergenos-tbody">
                        <!-- Los datos de los alérgenos se llenarán aquí usando AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
      <!-- Formulario para añadir un nuevo alérgeno -->
        <div class="mt-5">
            <h2>Añadir Alérgeno</h2>
            <form id="form-nuevo-alergeno" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nombreAlergeno">Nombre del Alérgeno</label>
                    <input type="text" class="form-control" id="nombreAlergeno" name="nombreAlergeno" required>
                </div>
                <div class="form-group">
                    <label for="fotoAlergeno">Foto del Alérgeno</label>
                    <input type="file" class="form-control-file" id="fotoAlergeno" name="fotoAlergeno" required>
                </div>
                <div class="form-group">
                    <label for="descripcionAlergeno">Descripción</label>
                    <textarea class="form-control" id="descripcionAlergeno" name="descripcionAlergeno" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Añadir Alérgeno</button>
            </form>
        </div>
    </div>
    <script src="./js/mantenimientoIngredientes.js"></script>
    
</body>
</html>
