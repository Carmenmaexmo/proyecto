<?php
require_once '../Repositorios/Conexion.php';
require_once '../Repositorios/RepoDireccion.php';
require_once '../Clases/Direccion.php';
require_once '../Clases/Usuario.php';

class ApiDireccion {
    private $repoDireccion;

    public function __construct($db) {
        $this->repoDireccion = new RepoDireccion($db);
    }

    public function handleRequest() {
        // Obtención del método HTTP
        $method = $_SERVER['REQUEST_METHOD'];

        // Obtención del endpoint
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', trim($url, '/'));
        $idDireccion = isset($urlParts[5]) ? (int)$urlParts[5] : null; 

        // Switch para manejar las peticiones HTTP
        switch ($method) {
            case 'POST':
                // Crear una dirección
                if (!$idDireccion) {
                    $data = json_decode(file_get_contents("php://input"), true);
                    echo $this->createDireccion($data);
                }
                break;

            case 'GET':
                // Obtener todas las direcciones
                if (!$idDireccion) {
                    echo $this->getDirecciones();
                }
                // Obtener una dirección específica
                else {
                    echo $this->getDireccion($idDireccion);
                }
                break;

            case 'PUT':
                // Actualizar una dirección
                parse_str(file_get_contents("php://input"), $data);
                if ($idDireccion && $data) {
                    echo $this->updateDireccion($idDireccion, $data);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "Datos no proporcionados correctamente"]);
                }
                break;

            case 'DELETE':
                // Eliminar una dirección
                if ($idDireccion) {
                    echo $this->deleteDireccion($idDireccion);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "ID de dirección no proporcionado"]);
                }
                break;

            default:
                $this->sendResponse(405, ["status" => "error", "message" => "Método no permitido"]);
                break;
        }
    }

    // Crear una dirección
    private function createDireccion($data) {
        if (!isset($data['direccion']) || !isset($data['estado']) || !isset($data['usuario'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (direccion, estado, usuario)"]);
        }

        $direccionData = [
            'direccion' => $data['direccion'],
            'estado' => $data['estado'],
            'usuario' => $data['usuario']
        ];

        $idDireccion = $this->repoDireccion->createDireccion($direccionData);

        if ($idDireccion) {
            return $this->sendResponse(201, ["status" => "success", "message" => "Dirección creada correctamente", "id" => $idDireccion]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al crear dirección"]);
    }

    // Obtener todas las direcciones
    private function getDirecciones() {
        $direcciones = $this->repoDireccion->getAllDirecciones();
        return $this->sendResponse(200, $direcciones);
    }

    // Obtener una dirección específica por ID
    private function getDireccion($idDireccion) {
        $direccion = $this->repoDireccion->getDireccionById($idDireccion);

        if ($direccion) {
            return $this->sendResponse(200, $direccion);
        }

        return $this->sendResponse(404, ["status" => "error", "message" => "Dirección no encontrada"]);
    }

    // Actualizar una dirección
    private function updateDireccion($idDireccion, $data) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['direccion']) || !isset($data['estado']) || !isset($data['usuario'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (direccion, estado, usuario)"]);
        }

        $direccionData = [
            'direccion' => $data['direccion'],
            'estado' => $data['estado'],
            'usuario' => $data['usuario']
        ];

        $updated = $this->repoDireccion->updateDireccion($idDireccion, $direccionData);

        if ($updated) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Dirección actualizada correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al actualizar dirección"]);
    }

    // Eliminar una dirección
    private function deleteDireccion($idDireccion) {
        $deleted = $this->repoDireccion->deleteDireccion($idDireccion);

        if ($deleted) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Dirección eliminada correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al eliminar dirección"]);
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
$apiDireccion = new ApiDireccion($db);
$apiDireccion->handleRequest();
?>
