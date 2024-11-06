<?php
require_once 'config/db.php';

class Kebab {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Crear un kebab predefinido
    public function create($data) {
        $query = "INSERT INTO kebab (nombre, foto, precio, descripcion) VALUES (:nombre, :foto, :precio, :descripcion)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':foto', $data['foto']);
        $stmt->bindParam(':precio', $data['precio']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->execute();
    }

    // Obtener todos los kebabs
    public function getAll() {
        $query = "SELECT * FROM kebab";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener un kebab por ID
    public function getById($id) {
        $query = "SELECT * FROM kebab WHERE idKebab = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
