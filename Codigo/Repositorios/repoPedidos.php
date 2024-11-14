<?php
require_once 'Conexion.php';
require_once '../Clases/Pedidos.php';
require_once '../Clases/Usuario.php';

class RepoPedidos {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db->getConnection();
    }

    // Crear un pedido
    public function createPedido($pedidoData) {
        $stmt = $this->conexion->prepare("INSERT INTO Pedidos (estado, fechaHora, precioTotal, Usuario_idUsuario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $pedidoData['estado'], $pedidoData['fechaHora'], $pedidoData['precioTotal'], $pedidoData['usuario']);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            return null;
        }

        return $this->conexion->insert_id; // Devuelve el ID del pedido creado
    }

    // Actualizar un pedido
    public function updatePedido($idPedido, $pedidoData) {
        $stmt = $this->conexion->prepare("UPDATE Pedidos SET estado = ?, fechaHora = ?, precioTotal = ?, Usuario_idUsuario = ? WHERE idPedidos = ?");
        $stmt->bind_param("ssdii", $pedidoData['estado'], $pedidoData['fechaHora'], $pedidoData['precioTotal'], $pedidoData['usuario'], $idPedido);
        $stmt->execute();

        return $stmt->affected_rows > 0; // Devuelve true si la actualización fue exitosa
    }

    // Eliminar un pedido
    public function deletePedido($idPedido) {
        $stmt = $this->conexion->prepare("DELETE FROM Pedidos WHERE idPedidos = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Obtener todos los pedidos
    public function getAllPedidos() {
        $stmt = $this->conexion->prepare("SELECT * FROM Pedidos");
        $stmt->execute();
        $result = $stmt->get_result();

        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }

        return $pedidos;
    }

    // Obtener un pedido por ID
    public function getPedidoById($idPedido) {
        $stmt = $this->conexion->prepare("SELECT * FROM Pedidos WHERE idPedidos = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }

    // Obtener todos los pedidos de un usuario
    public function getPedidosByUsuario($idUsuario) {
        $stmt = $this->conexion->prepare("SELECT * FROM Pedidos WHERE Usuario_idUsuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $result = $stmt->get_result();

        $pedidos = [];
        while ($row = $result->fetch_assoc()) {
            $pedidos[] = $row;
        }

        return $pedidos;
    }
}
?>
