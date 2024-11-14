<?php
require_once 'Conexion.php';
require_once '../Clases/LineaDePedido.php';
require_once '../Clases/Pedidos.php';

class RepoLineaDePedido {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db->getConnection();
    }

    // Crear una línea de pedido
    public function createLineaDePedido($lineaDePedidoData) {
        $stmt = $this->conexion->prepare("INSERT INTO LineaDePedido (cantidad, precio, lineaPedido, Pedidos_idPedidos) VALUES (?, ?, ?, ?)");
        $lineaPedidoJson = json_encode($lineaDePedidoData['lineaPedido']); // Convertir array a JSON
        $stmt->bind_param("idsi", $lineaDePedidoData['cantidad'], $lineaDePedidoData['precio'], $lineaPedidoJson, $lineaDePedidoData['pedido']);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            return null;
        }

        return $this->conexion->insert_id; // Devuelve el ID de la línea de pedido creada
    }

    // Actualizar una línea de pedido
    public function updateLineaDePedido($idLineaDePedido, $lineaDePedidoData) {
        $stmt = $this->conexion->prepare("UPDATE LineaDePedido SET cantidad = ?, precio = ?, lineaPedido = ?, Pedidos_idPedidos = ? WHERE idLinea_de_pedido = ?");
        
        // Asegúrate de que 'lineaPedido' sea un string JSON válido si es necesario
        $lineaPedidoJson = is_array($lineaDePedidoData['lineaPedido']) ? json_encode($lineaDePedidoData['lineaPedido']) : $lineaDePedidoData['lineaPedido'];
        
        $stmt->bind_param("idsii", $lineaDePedidoData['cantidad'], $lineaDePedidoData['precio'], $lineaPedidoJson, $lineaDePedidoData['pedido'], $idLineaDePedido);
        $stmt->execute();
    
        return $stmt->affected_rows > 0; // Retorna true si la actualización fue exitosa
    }

    // Eliminar una línea de pedido
    public function deleteLineaDePedido($idLineaDePedido) {
        $stmt = $this->conexion->prepare("DELETE FROM LineaDePedido WHERE idLinea_de_pedido = ?");
        $stmt->bind_param("i", $idLineaDePedido);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Obtener todas las líneas de pedido
    public function getAllLineasDePedido() {
        $stmt = $this->conexion->prepare("SELECT * FROM LineaDePedido");
        $stmt->execute();
        $result = $stmt->get_result();

        $lineasDePedido = [];
        while ($row = $result->fetch_assoc()) {
            $lineasDePedido[] = $row;
        }

        return $lineasDePedido;
    }

    // Obtener una línea de pedido por ID
    public function getLineaDePedidoById($idLineaDePedido) {
        $stmt = $this->conexion->prepare("SELECT * FROM LineaDePedido WHERE idLineaDePedido = ?");
        $stmt->bind_param("i", $idLineaDePedido);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }

    // Obtener todas las líneas de pedido de un pedido
    public function getLineasDePedidoByPedido($idPedido) {

        $stmt = $this->conexion->prepare("SELECT * FROM LineaDePedido WHERE Pedidos_idPedidos = ?");
        $stmt->bind_param("i", $idPedido);
        $stmt->execute();
        $result = $stmt->get_result();
        echo "Lineas de pedido por pedido";

        $lineasDePedido = [];
        while ($row = $result->fetch_assoc()) {
            $lineasDePedido[] = $row;
        }

        return $lineasDePedido;
    }
}
?>
