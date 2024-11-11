<?php
require_once '../Repositorios/conexion.php';
require_once '../Repositorios/repoKebab.php';
require_once '../Clases/kebab.php';

$conn = (new DB())->getConnection();
$repoKebab = new RepoKebab($conn);

header('Content-Type: application/json');

// Crear un nuevo kebab (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    // Verificar que todos los campos estén presentes en la solicitud
    if (isset($data['nombre'], $data['foto'], $data['precio'], $data['descripcion'])) {
        $kebab = new Kebab(null, $data['nombre'], $data['foto'], $data['precio'], $data['descripcion']);
        $id = $repoKebab->create($kebab);
        echo json_encode(['idKebab' => $id, 'status' => 'Kebab creado']);
    } else {
        echo json_encode(['error' => 'Datos incompletos para crear el kebab']);
    }
}

// Obtener kebab por ID (GET)
elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['idKebab'])) {
        $kebab = $repoKebab->getById($_GET['idKebab']);
        if ($kebab) {
            echo json_encode([
                'idKebab' => $kebab->getIdKebab(),
                'nombre' => $kebab->getNombre(),
                'foto' => $kebab->getFoto(),
                'precio' => $kebab->getPrecio(),
                'descripcion' => $kebab->getDescripcion()
            ]);
        } else {
            echo json_encode(['error' => 'Kebab no encontrado']);
        }
    } else {
        echo json_encode(['error' => 'ID de kebab no proporcionado']);
    }
}

// Actualizar un kebab existente (PUT)
elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    if (isset($_GET['idKebab'])) {
        $data = json_decode(file_get_contents("php://input"), true);

        // Verificar que todos los campos estén presentes
        if (isset($data['nombre'], $data['foto'], $data['precio'], $data['descripcion'])) {
            // Recuperar el kebab por ID y actualizar sus campos
            $kebab = new Kebab(
                $_GET['idKebab'], // ID del kebab a actualizar
                $data['nombre'],
                $data['foto'],
                $data['precio'],
                $data['descripcion']
            );

            $updated = $repoKebab->update($kebab);

            if ($updated) {
                echo json_encode(['status' => 'Kebab actualizado']);
            } else {
                echo json_encode(['error' => 'No se pudo actualizar el kebab o no se encontraron cambios']);
            }
        } else {
            echo json_encode(['error' => 'Datos incompletos para actualizar el kebab']);
        }
    } else {
        echo json_encode(['error' => 'ID de kebab no proporcionado']);
    }
}

// Eliminar un kebab (DELETE)
elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($_GET['idKebab'])) {
        $deleted = $repoKebab->delete($_GET['idKebab']);
        
        if ($deleted) {
            echo json_encode(['status' => 'Kebab eliminado']);
        } else {
            echo json_encode(['error' => 'No se pudo eliminar el kebab']);
        }
    } else {
        echo json_encode(['error' => 'ID de kebab no proporcionado']);
    }
}
