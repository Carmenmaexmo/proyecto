<?php
require_once 'config/db.php';

class Ingredientes {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Crear un ingrediente
    public function create($data) {
        $query = "INSERT INTO ingredientes (nombre, foto, precio, tipo, descripcion)
                  VALUES (:nombre, :foto, :precio, :tipo, :descripcion)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':foto', $data['foto']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':tipo', $data['tipo']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->execute();
    }

    // Obtener todos los ingredientes
    public function getAll() {
        $query = "SELECT * FROM ingredientes";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
