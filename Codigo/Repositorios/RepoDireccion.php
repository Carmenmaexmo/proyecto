<?php
require_once 'Conexion.php';
require_once '../Clases/Direccion.php';
require_once '../Clases/Usuario.php';

class RepoDireccion {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db->getConnection();
    }

    // Crear una dirección
    public function createDireccion($direccionData) {
        $stmt = $this->conexion->prepare("INSERT INTO Direccion (direccion, estado, Usuario_idUsuario) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $direccionData['direccion'], $direccionData['estado'], $direccionData['usuario']);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            return null;
        }

        return $this->conexion->insert_id; // Devuelve el ID de la dirección creada
    }

    // Actualizar una dirección
    public function updateDireccion($idDireccion, $direccionData) {
        $stmt = $this->conexion->prepare("UPDATE Direccion SET direccion = ?, estado = ?, Usuario_idUsuario = ? WHERE idDireccion = ?");
        $stmt->bind_param("ssii", $direccionData['direccion'], $direccionData['estado'], $direccionData['usuario'], $idDireccion);
        $stmt->execute();
        return $stmt->affected_rows > 0; // Devuelve true si la actualización fue exitosa
    }

    // Eliminar una dirección
    public function deleteDireccion($idDireccion) {
        $stmt = $this->conexion->prepare("DELETE FROM Direccion WHERE idDireccion = ?");
        $stmt->bind_param("i", $idDireccion);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Obtener todas las direcciones
    public function getAllDirecciones() {
        $stmt = $this->conexion->prepare("SELECT * FROM Direccion");
        $stmt->execute();
        $result = $stmt->get_result();

        $direcciones = [];
        while ($row = $result->fetch_assoc()) {
            $direcciones[] = $row;
        }

        return $direcciones;
    }

    // Obtener una dirección por ID
    public function getDireccionById($idDireccion) {
        $stmt = $this->conexion->prepare("SELECT * FROM Direccion WHERE idDireccion = ?");
        $stmt->bind_param("i", $idDireccion);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        return $result->fetch_assoc();
    }
}
?>
