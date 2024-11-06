<?php
require_once 'config/db.php';

class Pedidos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Crear un nuevo pedido
    public function create($data) {
        $query = "INSERT INTO pedidos (estado, fechaHora, precioTotal, Usuario_idUsuario) VALUES (:estado, :fechaHora, :precioTotal, :usuarioId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $data['estado']);
        $stmt->bindParam(':fechaHora', $data['fechaHora']);
        $stmt->bindParam(':precioTotal', $data['precioTotal']);
        $stmt->bindParam(':usuarioId', $data['Usuario_idUsuario']);
        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    // Actualizar el estado de un pedido
    public function updateStatus($id, $status) {
        $query = "UPDATE pedidos SET estado = :estado WHERE idPedidos = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Obtener todos los pedidos de un usuario
    public function getOrdersByUserId($userId) {
        $query = "SELECT * FROM pedidos WHERE Usuario_idUsuario = :userId";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
