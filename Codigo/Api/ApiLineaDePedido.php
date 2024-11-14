<?php
require_once '../Repositorios/Conexion.php';
require_once '../Repositorios/RepoLineaDePedido.php';
require_once '../Clases/LineaDePedido.php';
require_once '../Clases/Pedidos.php';

class ApiLineaDePedido {
    private $repoLineaDePedido;

    public function __construct($db) {
        $this->repoLineaDePedido = new RepoLineaDePedido($db);
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', trim($url, '/'));
    
        $idPedido = isset($urlParts[6]) ? (int)$urlParts[6] : null;

        // Obtener idLineaDePedido desde la última parte de la URL
        $idLineaDePedido = isset($urlParts[count($urlParts) - 1]) ? (int)$urlParts[count($urlParts) - 1] : null;

        // Switch para manejar las peticiones HTTP
        switch ($method) {
            case 'POST':
                // Crear una línea de pedido
                if (!$idLineaDePedido) {
                    $data = json_decode(file_get_contents("php://input"), true);
                    echo $this->createLineaDePedido($data);
                }
                break;

            case 'GET':
                 // Obtener todas las líneas de pedido de un pedido si $idPedido está definido
                if ($idPedido) {
                    echo $this->getLineasDePedidoByPedido($idPedido);
                }
                // Obtener una línea de pedido específica por ID si $idLineaDePedido está definido
                else if ($idLineaDePedido) {
                    echo $this->getLineaDePedido($idLineaDePedido);
                }
                // Obtener todas las líneas de pedido si no hay parámetros
                else {
                    echo $this->getLineasDePedido();
                }
                break;

            case 'PUT':
            // Verifica que la URL tenga un id y que el método sea PUT
            if ($idLineaDePedido) {
                $data = json_decode(file_get_contents("php://input"), true);
                if ($data) {
                    echo $this->updateLineaDePedido($idLineaDePedido, $data);
                } else {
                    echo json_encode(["status" => "error", "message" => "Datos no proporcionados correctamente"]);
                }
                } else {
                    // Error si no se pasa un id
                    echo json_encode(["status" => "error", "message" => "ID de línea de pedido no proporcionado"]);
                }
            break;

            case 'DELETE':
                // Eliminar una línea de pedido
                if ($idLineaDePedido) {
                    echo $this->deleteLineaDePedido($idLineaDePedido);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "ID de línea de pedido no proporcionado"]);
                }
                break;

            default:
                $this->sendResponse(405, ["status" => "error", "message" => "Método no permitido"]);
                break;
        }
    }

    // Crear una línea de pedido
    private function createLineaDePedido($data) {
        if (!isset($data['cantidad']) || !isset($data['precio']) || !isset($data['pedido']) || !isset($data['lineaPedido'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (cantidad, precio, pedido, lineaPedido)"]);
        }

        $lineaDePedidoData = [
            'cantidad' => $data['cantidad'],
            'precio' => $data['precio'],
            'lineaPedido' => $data['lineaPedido'],
            'pedido' => $data['pedido']
        ];

        $idLineaDePedido = $this->repoLineaDePedido->createLineaDePedido($lineaDePedidoData);

        if ($idLineaDePedido) {
            return $this->sendResponse(201, ["status" => "success", "message" => "Línea de pedido creada correctamente", "id" => $idLineaDePedido]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al crear línea de pedido"]);
    }

    // Obtener todas las líneas de pedido
    private function getLineasDePedido() {
        $lineasDePedido = $this->repoLineaDePedido->getAllLineasDePedido();
        return $this->sendResponse(200, $lineasDePedido);
    }

    // Obtener una línea de pedido específica por ID
    private function getLineaDePedido($idLineaDePedido) {
        $lineaDePedido = $this->repoLineaDePedido->getLineaDePedidoById($idLineaDePedido);

        if ($lineaDePedido) {
            return $this->sendResponse(200, $lineaDePedido);
        }

        return $this->sendResponse(404, ["status" => "error", "message" => "Línea de pedido no encontrada"]);
    }

    // Obtener todas las líneas de pedido de un pedido
    private function getLineasDePedidoByPedido($idPedido) {
        $lineasDePedido = $this->repoLineaDePedido->getLineasDePedidoByPedido($idPedido);

        if ($lineasDePedido) {
            return $this->sendResponse(200, $lineasDePedido);
        }

        return $this->sendResponse(404, ["status" => "error", "message" => "Líneas de pedido no encontradas para el pedido especificado"]);
    }

    // Actualizar una línea de pedido
    private function updateLineaDePedido($idLineaDePedido, $data) {
        // Validar si los datos obligatorios están presentes
        if (!isset($data['cantidad']) || !isset($data['precio']) || !isset($data['lineaPedido']) || !isset($data['pedido'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (cantidad, precio, lineaPedido, pedido)"]);
        }

        // Preparar los datos para la actualización
        $lineaDePedidoData = [
            'cantidad' => $data['cantidad'],
            'precio' => $data['precio'],
            'lineaPedido' => is_array($data['lineaPedido']) ? json_encode($data['lineaPedido']) : $data['lineaPedido'], // Aseguramos que 'lineaPedido' sea un JSON válido
            'pedido' => $data['pedido']
        ];
   
        // Llamada al repositorio para actualizar la línea de pedido
        $updated = $this->repoLineaDePedido->updateLineaDePedido($idLineaDePedido, $lineaDePedidoData);

        // Verificar si la actualización fue exitosa
        if ($updated) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Línea de pedido actualizada correctamente"]);
        }

        // Si hubo un error en la actualización
        return $this->sendResponse(500, ["status" => "error", "message" => "Error al actualizar línea de pedido"]);
    }


    // Eliminar una línea de pedido
    private function deleteLineaDePedido($idLineaDePedido) {
        $deleted = $this->repoLineaDePedido->deleteLineaDePedido($idLineaDePedido);

        if ($deleted) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Línea de pedido eliminada correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al eliminar línea de pedido"]);
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
$apiLineaDePedido = new ApiLineaDePedido($db);

// Ejecutamos el manejador de la solicitud
$apiLineaDePedido->handleRequest();
