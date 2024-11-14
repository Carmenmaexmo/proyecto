<?php
require_once '../Repositorios/Conexion.php';
require_once '../Repositorios/RepoPedidos.php';
require_once '../Clases/Pedidos.php';
require_once '../Clases/Usuario.php';

class ApiPedidos {
    private $repoPedidos;

    public function __construct($db) {
        $this->repoPedidos = new RepoPedidos($db);
    }

    public function handleRequest() {
        // Obtención del método HTTP
        $method = $_SERVER['REQUEST_METHOD'];

        // Obtención del endpoint
        $url = $_SERVER['REQUEST_URI'];
        $urlParts = explode('/', trim($url, '/'));
        $idPedido = isset($urlParts[5]) ? (int)$urlParts[5] : null; // Ajustar índice según la estructura de la URL
        $idUsuario = isset($urlParts[6]) ? (int)$urlParts[6] : null;

        // Switch para manejar las peticiones HTTP
        switch ($method) {
            case 'POST':
                // Crear un pedido
                if (!$idPedido) {
                    $data = json_decode(file_get_contents("php://input"), true);
                    echo $this->createPedido($data);
                }
                break;

            case 'GET':
                // Obtener todos los pedidos
                if (!$idPedido && !$idUsuario) {
                    echo $this->getPedidos();
                }
                // Obtener un pedido específico por ID
                else if ($idPedido) {
                    echo $this->getPedido($idPedido);
                }
                // Obtener todos los pedidos de un usuario
                else if ($idUsuario) {
                    echo $this->getPedidosByUsuario($idUsuario);
                }
                break;

            case 'PUT':
                // Actualizar un pedido
                $data = json_decode(file_get_contents("php://input"), true);
                if ($idPedido && $data) {
                    echo $this->updatePedido($idPedido, $data);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "Datos no proporcionados correctamente"]);
                }
                break;

            case 'DELETE':
                // Eliminar un pedido
                if ($idPedido) {
                    echo $this->deletePedido($idPedido);
                } else {
                    http_response_code(400);
                    echo json_encode(["status" => "error", "message" => "ID de pedido no proporcionado"]);
                }
                break;

            default:
                $this->sendResponse(405, ["status" => "error", "message" => "Método no permitido"]);
                break;
        }
    }

    // Crear un pedido
    private function createPedido($data) {
        if (!isset($data['estado']) || !isset($data['fechaHora']) || !isset($data['precioTotal']) || !isset($data['usuario'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (estado, fechaHora, precioTotal, usuario)"]);
        }

        $pedidoData = [
            'estado' => $data['estado'],
            'fechaHora' => $data['fechaHora'],
            'precioTotal' => $data['precioTotal'],
            'usuario' => $data['usuario']
        ];

        $idPedido = $this->repoPedidos->createPedido($pedidoData);

        if ($idPedido) {
            return $this->sendResponse(201, ["status" => "success", "message" => "Pedido creado correctamente", "id" => $idPedido]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al crear pedido"]);
    }

    // Obtener todos los pedidos
    private function getPedidos() {
        $pedidos = $this->repoPedidos->getAllPedidos();
        return $this->sendResponse(200, $pedidos);
    }

    // Obtener un pedido específico por ID
    private function getPedido($idPedido) {
        $pedido = $this->repoPedidos->getPedidoById($idPedido);

        if ($pedido) {
            return $this->sendResponse(200, $pedido);
        }

        return $this->sendResponse(404, ["status" => "error", "message" => "Pedido no encontrado"]);
    }

    // Obtener todos los pedidos de un usuario
    private function getPedidosByUsuario($idUsuario) {
        $pedidos = $this->repoPedidos->getPedidosByUsuario($idUsuario);

        if ($pedidos) {
            return $this->sendResponse(200, $pedidos);
        }

        return $this->sendResponse(404, ["status" => "error", "message" => "Pedidos no encontrados para el usuario especificado"]);
    }

    // Actualizar un pedido
    private function updatePedido($idPedido, $data) {
        if (!isset($data['estado']) || !isset($data['fechaHora']) || !isset($data['precioTotal']) || !isset($data['usuario'])) {
            return $this->sendResponse(400, ["status" => "error", "message" => "Faltan datos obligatorios (estado, fechaHora, precioTotal, usuario)"]);
        }

        $pedidoData = [
            'estado' => $data['estado'],
            'fechaHora' => $data['fechaHora'],
            'precioTotal' => $data['precioTotal'],
            'usuario' => $data['usuario']
        ];

        $updated = $this->repoPedidos->updatePedido($idPedido, $pedidoData);

        if ($updated) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Pedido actualizado correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al actualizar pedido"]);
    }

    // Eliminar un pedido
    private function deletePedido($idPedido) {
        $deleted = $this->repoPedidos->deletePedido($idPedido);

        if ($deleted) {
            return $this->sendResponse(200, ["status" => "success", "message" => "Pedido eliminado correctamente"]);
        }

        return $this->sendResponse(500, ["status" => "error", "message" => "Error al eliminar pedido"]);
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
$apiPedidos = new ApiPedidos($db);

// Ejecutamos el manejador de la solicitud
$apiPedidos->handleRequest();
