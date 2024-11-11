<?php
class RepoPedidos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Crear un nuevo pedido
    public function create(Pedidos $pedido) {
        $query = "INSERT INTO pedidos (estado, fechaHora, precioTotal, Usuario_idUsuario) VALUES (:estado, :fechaHora, :precioTotal, :usuarioId)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':estado', $pedido->getEstado());
        $stmt->bindParam(':fechaHora', $pedido->getFechaHora());
        $stmt->bindParam(':precioTotal', $pedido->getPrecioTotal());
        $stmt->bindParam(':usuarioId', $pedido->getUsuarioId());

        if ($stmt->execute()) {
            return $this->conn->lastInsertId(); // Retorna el ID del nuevo pedido
        }
        return false;
    }

    // Obtener un pedido por su ID
    public function getById($idPedido) {
        $query = "SELECT * FROM pedidos WHERE idPedido = :idPedido";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idPedido', $idPedido);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new Pedidos(
                $result['idPedido'],
                $result['estado'],
                $result['fechaHora'],
                $result['precioTotal'],
                $result['Usuario_idUsuario']
            );
        }
        return null; // Si no se encuentra el pedido
    }

    // Actualizar un pedido existente
    public function update(Pedidos $pedido) {
        $query = "UPDATE pedidos SET estado = :estado, fechaHora = :fechaHora, precioTotal = :precioTotal, Usuario_idUsuario = :usuarioId WHERE idPedido = :idPedido";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':estado', $pedido->getEstado());
        $stmt->bindParam(':fechaHora', $pedido->getFechaHora());
        $stmt->bindParam(':precioTotal', $pedido->getPrecioTotal());
        $stmt->bindParam(':usuarioId', $pedido->getUsuarioId());
        $stmt->bindParam(':idPedido', $pedido->getIdPedidos());

        return $stmt->execute();
    }

    // Eliminar un pedido por su ID
    public function delete($idPedido) {
        $query = "DELETE FROM pedidos WHERE idPedido = :idPedido";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idPedido', $idPedido);
        return $stmt->execute();
    }
}
