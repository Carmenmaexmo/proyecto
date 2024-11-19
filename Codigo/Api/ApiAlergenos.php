<?php
require_once '../Repositorios/Conexion.php';
require_once '../Repositorios/RepoAlergenos.php';
require_once '../Clases/Alergenos.php';

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
        $urlParts = explode('/', trim($url, '/'));
        $idAlergeno = isset($urlParts[4]) ? (int)$urlParts[4] : null; 

        // Switch para manejar las peticiones HTTP
        switch ($method) {
            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo $this->createAlergeno($data);
                break;

            case 'GET':
                if ($idAlergeno) {
                    echo $this->getAlergeno($idAlergeno);
                } else {
                    echo $this->getAllAlergenos();
                }
                break;

            case 'PUT':
                if ($idAlergeno) {
                    parse_str(file_get_contents("php://input"), $data);
                    echo $this->updateAlergeno($idAlergeno, $data);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "ID de alérgeno no proporcionado"]);
                }
                break;

            case 'DELETE':
                if ($idAlergeno) {
                    echo $this->deleteAlergeno($idAlergeno);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "ID de alérgeno no proporcionado"]);
                }
                break;

            default:
                $this->sendResponse(405, ["status" => "error", "message" => "Método no permitido"]);
                break;
        }
    }

    private function createAlergeno($data) {
        if (!isset($data['nombre']) || !isset($data['descripcion'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (nombre, descripción)"]);
        }

        $alergenoData = [
            'nombre' => $data['nombre'],
            'foto' => $data['foto'] ?? null,
            'descripcion' => $data['descripcion']
        ];

        $idAlergeno = $this->repoAlergenos->createAlergeno($alergenoData);

        if ($idAlergeno) {
            return $this->sendResponse(201, ["status" => "success", "message" => "Alérgeno creado correctamente", "id" => $idAlergeno]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al crear alérgeno"]);
    }

    private function getAllAlergenos() {
        $alergenos = $this->repoAlergenos->getAllAlergenos();
        return $this->sendResponse(200, $alergenos);
    }

    private function getAlergeno($idAlergeno) {
        $alergeno = $this->repoAlergenos->getAlergenoById($idAlergeno);

        if ($alergeno) {
            return $this->sendResponse(200, $alergeno);
        }

        return $this->sendResponse(404, ["status" => "error", "message" => "Alérgeno no encontrado"]);
    }

    private function updateAlergeno($idAlergeno, $data) {
        if (!isset($data['nombre']) || !isset($data['descripcion'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (nombre, descripción)"]);
        }

        $alergenoData = [
            'nombre' => $data['nombre'],
            'foto' => $data['foto'] ?? null,
            'descripcion' => $data['descripcion']
        ];

        $updated = $this->repoAlergenos->updateAlergeno($idAlergeno, $alergenoData);

        if ($updated) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Alérgeno actualizado correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al actualizar alérgeno"]);
    }

    private function deleteAlergeno($idAlergeno) {
        $deleted = $this->repoAlergenos->deleteAlergeno($idAlergeno);

        if ($deleted) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Alérgeno eliminado correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al eliminar alérgeno"]);
    }

    private function sendResponse($statusCode, $response) {
        http_response_code($statusCode);
        echo json_encode($response);
        exit();
    }
}

// Inicialización y manejo de la API
$db = new DB();
$apiAlergenos = new ApiAlergenos($db);
$apiAlergenos->handleRequest();
