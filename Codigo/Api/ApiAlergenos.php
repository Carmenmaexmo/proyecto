<?php
require_once '../Repositorios/conexion.php';
require_once '../Repositorios/RepoAlergenos.php';
require_once '../Clases/Alergenos.php';
require_once '../Clases/Usuario.php';
require_once '../Clases/Ingredientes.php';

class ApiAlergenos {
    private $repoAlergenos;

    public function __construct($db) {
        $this->repoAlergenos = new RepoAlergenos($db);
    }

    public function handleRequest() {
        // Obtención del método HTTP
        $method = $_SERVER['REQUEST_METHOD'];

        // Obtención del endpoint
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', $url);
        $idAlergeno = isset($urlParts[2]) ? (int)$urlParts[2] : null;
        $idUsuario = isset($urlParts[3]) ? (int)$urlParts[3] : null;
        $idIngrediente = isset($urlParts[4]) ? (int)$urlParts[4] : null;

        // Switch para manejar las peticiones HTTP
        switch ($method) {
            case 'POST':
                // Crear un alérgeno
                if (!$idAlergeno && !$idUsuario && !$idIngrediente) {
                    $data = json_decode(file_get_contents("php://input"), true);
                    echo $this->createAlergeno($data);
                }
                // Asociar un usuario a un alérgeno
                else if ($idAlergeno && $idUsuario) {
                    echo $this->addUsuarioToAlergeno($idAlergeno, $idUsuario);
                }
                // Asociar un ingrediente a un alérgeno
                else if ($idAlergeno && $idIngrediente) {
                    echo $this->addIngredienteToAlergeno($idAlergeno, $idIngrediente);
                }
                break;

            case 'GET':
                // Obtener todos los alérgenos
                if (!$idAlergeno) {
                    echo $this->getAlergenos();
                }
                // Obtener un alérgeno específico
                else {
                    echo $this->getAlergeno($idAlergeno);
                }
                break;

            case 'PUT':
                // Actualizar un alérgeno
                if (isset($_GET['id'])) {
                    $idAlergeno = $_GET['id'];
                    $data = json_decode(file_get_contents("php://input"), true);
                    echo $this->updateAlergeno($idAlergeno, $data);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "ID de alérgeno no proporcionado"]);
                }
                break;
                
            case 'DELETE':
                // Eliminar un alérgeno
                if (isset($_GET['id'])) {
                    $idAlergeno = $_GET['id'];
                    $data = json_decode(file_get_contents("php://input"), true);
                    echo $this->deleteAlergeno($idAlergeno);
                }
                break;

            default:
                $this->sendResponse(405, ["status" => "error", "message" => "Método no permitido"]);
                break;
        }
    }

    // Crear un alérgeno
    private function createAlergeno($data) {
        if (!isset($data['nombre']) || !isset($data['descripcion'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (nombre, descripcion)"]);
        }

        $alergenoData = [
            'nombre' => $data['nombre'],
            'foto' => $data['foto'] ?? null,
            'descripcion' => $data['descripcion']
        ];

        $idAlergeno = $this->repoAlergenos->createAlergeno($alergenoData);

        if ($idAlergeno) {
            return $this->sendResponse(201, ["status" => "success", "message" => "Alergeno creado correctamente", "id" => $idAlergeno]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al crear alérgeno"]);
    }

    // Obtener todos los alérgenos
    private function getAlergenos() {
        $alergenos = $this->repoAlergenos->getAllAlergenos();
        return $this->sendResponse(200, $alergenos);
    }

    // Obtener un alérgeno específico por ID
    private function getAlergeno($idAlergeno) {
        $alergeno = $this->repoAlergenos->getAlergenoById($idAlergeno);

        if ($alergeno) {
            return $this->sendResponse(200, $alergeno);
        }

        return $this->sendResponse(404, ["status" => "error", "message" => "Alergeno no encontrado"]);
    }

    // Actualizar un alérgeno
    private function updateAlergeno($idAlergeno, $data) {
        if (!isset($data['nombre']) || !isset($data['descripcion'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (nombre, descripcion)"]);
        }

        $alergenoData = [
            'nombre' => $data['nombre'],
            'foto' => $data['foto'] ?? null,
            'descripcion' => $data['descripcion']
        ];

        $updated = $this->repoAlergenos->updateAlergeno($idAlergeno, $alergenoData);

        if ($updated) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Alergeno actualizado correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al actualizar alérgeno"]);
    }

    // Asociar un usuario a un alérgeno
    private function addUsuarioToAlergeno($idAlergeno, $idUsuario) {
        $added = $this->repoAlergenos->addUsuarioToAlergeno($idAlergeno, $idUsuario);

        if ($added) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Usuario asociado correctamente al alérgeno"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al asociar usuario al alérgeno"]);
    }

    // Asociar un ingrediente a un alérgeno
    private function addIngredienteToAlergeno($idAlergeno, $idIngrediente) {
        $added = $this->repoAlergenos->addIngredienteToAlergeno($idAlergeno, $idIngrediente);

        if ($added) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Ingrediente asociado correctamente al alérgeno"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al asociar ingrediente al alérgeno"]);
    }

    // Eliminar un alérgeno
    private function deleteAlergeno($idAlergeno) {
        $deleted = $this->repoAlergenos->deleteAlergeno($idAlergeno);

        if ($deleted) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Alergeno eliminado correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al eliminar alérgeno"]);
    }

    // Función para enviar respuestas JSON con códigos de estado adecuados
    private function sendResponse($statusCode, $response) {
        http_response_code($statusCode);
        echo json_encode($response);
        exit();
    }
}

// Instanciamos las clases y ejecutamos
$db = new DB();
$apiAlergenos = new ApiAlergenos($db);
$apiAlergenos->handleRequest();
?>
