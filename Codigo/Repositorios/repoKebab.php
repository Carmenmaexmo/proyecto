<?php
require_once 'conexion.php';
require_once '../Clases/kebab.php';

class RepoKebab {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function create(Kebab $kebab) {
        $stmt = $this->conn->prepare("INSERT INTO Kebab (nombre, foto, precio, descripcion) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $kebab->getNombre(),
            $kebab->getFoto(),
            $kebab->getPrecio(),
            $kebab->getDescripcion()
        ]);
        return $this->conn->lastInsertId();
    }

    public function getById($idKebab) {
        $stmt = $this->conn->prepare("SELECT * FROM Kebab WHERE idKebab = ?");
        $stmt->execute([$idKebab]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return new Kebab(
                $result['idKebab'],
                $result['nombre'],
                $result['foto'],
                $result['precio'],
                $result['descripcion']
            );
        }
        return null;
    }

    public function update(Kebab $kebab) {
        $stmt = $this->conn->prepare("UPDATE Kebab SET nombre = ?, foto = ?, precio = ?, descripcion = ? WHERE idKebab = ?");
        return $stmt->execute([
            $kebab->getNombre(),
            $kebab->getFoto(),
            $kebab->getPrecio(),
            $kebab->getDescripcion(),
            $kebab->getIdKebab()
        ]);
    }

    public function delete($idKebab) {
        $stmt = $this->conn->prepare("DELETE FROM Kebab WHERE idKebab = ?");
        return $stmt->execute([$idKebab]);
    }
}
