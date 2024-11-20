<?php
require_once '../Repositorios/Conexion.php';
require_once '../Repositorios/RepoIngredientes.php';

class ApiIngredientes {
    private $repoIngredientes;

    public function __construct($db) {
        $this->repoIngredientes = new RepoIngredientes($db);
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', trim($url, '/'));
        $idIngrediente = isset($urlParts[5]) ? (int)$urlParts[5] : null;

        switch ($method) {
            case 'GET':
                if ($idIngrediente) {
                    echo $this->getIngrediente($idIngrediente);
                } else {
                    echo $this->getIngredientes();
                }
                break;

            case 'POST':
                $data = json_decode(file_get_contents("php://input"), true);
                echo $this->createIngrediente($data);
                break;

            case 'PUT':
                if ($idIngrediente) {
                    $data = json_decode(file_get_contents("php://input"), true);
                    error_log(print_r($data, true)); // Verifica los datos en el log
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
        if (!isset($data['nombre'], $data['precio'], $data['tipo'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (nombre, precio, tipo)"]);
        }
    
        // Validar Base64 para la imagen
        $fotoBase64 = $data['foto'] ?? null;
        if ($fotoBase64 && !$this->isValidBase64($fotoBase64)) {
            return $this->sendResponse(400, ["status" => "error", "message" => "La imagen no es válida"]);
        }
    
        $ingredienteData = [
            'nombre' => $data['nombre'],
            'foto' => $fotoBase64,
            'precio' => $data['precio'],
            'tipo' => $data['tipo']
        ];
    
        $alergenosIds = $data['alergenos'] ?? []; // Alérgenos opcionales
    
        $idIngrediente = $this->repoIngredientes->createIngrediente($ingredienteData, $alergenosIds);
    
        if ($idIngrediente) {
            return $this->sendResponse(201, ["status" => "success", "message" => "Ingrediente creado correctamente", "id" => $idIngrediente]);
        }
    
        return $this->sendResponse(500, ["status" => "error", "message" => "Error al crear ingrediente"]);
    }
    
    // Método auxiliar para validar Base64
    private function isValidBase64($base64) {
        $decoded = base64_decode($base64, true);
        return $decoded !== false && base64_encode($decoded) === $base64;
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

        $ingredienteData = [
            'nombre' => $data['nombre'],
            'foto' => $data['foto'] ?? null,
            'precio' => $data['precio'],
            'tipo' => $data['tipo']
        ];

        $updated = $this->repoIngredientes->updateIngrediente($idIngrediente, $ingredienteData);

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

    private function sendResponse($statusCode, $response) {
        http_response_code($statusCode);
        echo json_encode($response);
        exit();
    }
}

$db = new DB();
$apiIngredientes = new ApiIngredientes($db);
$apiIngredientes->handleRequest();
