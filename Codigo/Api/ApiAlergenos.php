<?php
require_once '../Repositorios/Conexion.php';
require_once '../Repositorios/RepoIngredientes.php';
require_once '../Clases/Ingredientes.php';
require_once '../Clases/Alergenos.php';

class ApiIngredientes {
    private $repoIngredientes;

    public function __construct($db) {
        $this->repoIngredientes = new RepoIngredientes($db);
    }

    public function handleRequest() {
        // Obtención del método HTTP
        $method = $_SERVER['REQUEST_METHOD'];

        // Obtención del endpoint
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', trim($url, '/'));
        $idIngrediente = isset($urlParts[4]) ? (int)$urlParts[4] : null; 

        // Switch para manejar las peticiones HTTP
        switch ($method) {
            case 'POST':
                if (isset($urlParts[5]) && $urlParts[5] === 'alergenos') {
                    $idAlergeno = (int)$urlParts[6];
                    echo $this->addAlergenoToIngrediente($idIngrediente, $idAlergeno);
                } else {
                    $data = json_decode(file_get_contents("php://input"), true);
                    echo $this->createIngrediente($data);
                }
                break;

            case 'GET':
                if ($idIngrediente) {
                    echo $this->getIngrediente($idIngrediente);
                } else {
                    echo $this->getIngredientes();
                }
                break;

            case 'PUT':
                if ($idIngrediente) {
                    parse_str(file_get_contents("php://input"), $data);
                    echo $this->updateIngrediente($idIngrediente, $data);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "ID de ingrediente no proporcionado"]);
                }
                break;

            case 'DELETE':
                if ($idIngrediente) {
                    echo $this->deleteIngrediente($idIngrediente);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "ID de ingrediente no proporcionado"]);
                }
                break;

            default:
                $this->sendResponse(405, ["status" => "error", "message" => "Método no permitido"]);
                break;
        }
    }

    private function createIngrediente($data) {
        if (!isset($data['nombre']) || !isset($data['precio']) || !isset($data['tipo'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (nombre, precio, tipo)"]);
        }

        $alergenosIds = isset($data['alergenos']) ? $data['alergenos'] : [];
        $ingredienteData = [
            'nombre' => $data['nombre'],
            'foto' => $data['foto'] ?? null,
            'precio' => $data['precio'],
            'tipo' => $data['tipo']
        ];

        $idIngrediente = $this->repoIngredientes->createIngrediente($ingredienteData, $alergenosIds);

        if ($idIngrediente) {
            return $this->sendResponse(201, ["status" => "success", "message" => "Ingrediente creado correctamente", "id" => $idIngrediente]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al crear ingrediente"]);
    }

    private function getIngredientes() {
        $ingredientes = $this->repoIngredientes->getAllIngredientes();
        return $this->sendResponse(200, $ingredientes);
    }

    private function getIngrediente($idIngrediente) {
        $ingrediente = $this->repoIngredientes->getIngredienteById($idIngrediente);

        if ($ingrediente) {
            return $this->sendResponse(200, $ingrediente);
        }

        return $this->sendResponse(404, ["status" => "error", "message" => "Ingrediente no encontrado"]);
    }

    private function updateIngrediente($idIngrediente, $data) {
        if (!isset($data['nombre']) || !isset($data['precio']) || !isset($data['tipo'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (nombre, precio, tipo)"]);
        }

        $alergenosIds = isset($data['alergenos']) ? $data['alergenos'] : [];
        $ingredienteData = [
            'nombre' => $data['nombre'],
            'foto' => $data['foto'] ?? null,
            'precio' => $data['precio'],
            'tipo' => $data['tipo']
        ];

        $updated = $this->repoIngredientes->updateIngrediente($idIngrediente, $ingredienteData, $alergenosIds);

        if ($updated) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Ingrediente actualizado correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al actualizar ingrediente"]);
    }

    private function deleteIngrediente($idIngrediente) {
        $deleted = $this->repoIngredientes->deleteIngrediente($idIngrediente);

        if ($deleted) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Ingrediente eliminado correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al eliminar ingrediente"]);
    }

    private function addAlergenoToIngrediente($idIngrediente, $idAlergeno) {
        $added = $this->repoIngredientes->addAlergenoToIngrediente($idIngrediente, $idAlergeno);

        if ($added) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Alérgeno asociado correctamente al ingrediente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al asociar alérgeno al ingrediente"]);
    }

    private function sendResponse($statusCode, $response) {
        http_response_code($statusCode);
        echo json_encode($response);
        exit();
    }
}

$db = new DB();
$apiIngredientes = new ApiIngredientes($db);
$apiIngredientes->handleRequest();