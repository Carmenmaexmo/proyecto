<?php
require_once 'Conexion.php';
require_once '../Clases/Kebab.php';
require_once '../Clases/Ingredientes.php';

class RepoKebab {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db->getConnection();
    }

    // Crear un kebab
    public function createKebab($kebabData, $ingredientesIds = []) {
        $stmt = $this->conexion->prepare("INSERT INTO Kebab (nombre, foto, precio, descripcion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $kebabData['nombre'], $kebabData['foto'], $kebabData['precio'], $kebabData['descripcion']);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            return null;
        }

        $kebabId = $this->conexion->insert_id; // Devuelve el ID del kebab creado

        // Asociar los ingredientes al kebab en la tabla Kebabs_has_Ingredientes
        foreach ($ingredientesIds as $ingredienteId) {
            $this->addIngredienteToKebab($kebabId, $ingredienteId);
        }

        return $kebabId;
    }

    // Actualizar un kebab
    public function updateKebab($idKebab, $kebabData, $ingredientesIds = []) {
        $stmt = $this->conexion->prepare("UPDATE Kebab SET nombre = ?, foto = ?, precio = ?, descripcion = ? WHERE idKebab = ?");
        $stmt->bind_param("ssdsi", $kebabData['nombre'], $kebabData['foto'], $kebabData['precio'], $kebabData['descripcion'], $idKebab);
        $stmt->execute();

        // Eliminar asociaciones actuales y agregar las nuevas
        $this->removeIngredientesFromKebab($idKebab);
        foreach ($ingredientesIds as $ingredienteId) {
            $this->addIngredienteToKebab($idKebab, $ingredienteId);
        }

        return $stmt->affected_rows > 0; // Devuelve true si la actualización fue exitosa
    }

    // Eliminar un kebab
    public function deleteKebab($idKebab) {
        // Primero, eliminamos las asociaciones en la tabla intermedia
        $this->removeIngredientesFromKebab($idKebab);

        // Luego, eliminamos el kebab
        $stmt = $this->conexion->prepare("DELETE FROM Kebab WHERE idKebab = ?");
        $stmt->bind_param("i", $idKebab);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Asociar un ingrediente a un kebab
    public function addIngredienteToKebab($kebabId, $ingredienteId) {
        $stmt = $this->conexion->prepare("INSERT INTO Kebab_has_Ingredientes (Kebab_idKebab, Ingredientes_idIngredientes) VALUES (?, ?)");
        $stmt->bind_param("ii", $kebabId, $ingredienteId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Eliminar asociaciones de ingredientes de un kebab
    public function removeIngredientesFromKebab($kebabId) {
        $stmt = $this->conexion->prepare("DELETE FROM Kebab_has_Ingredientes WHERE Kebab_idKebab = ?");
        $stmt->bind_param("i", $kebabId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Obtener todos los kebabs con sus ingredientes
     public function getAllKebabs() {
        $stmt = $this->conexion->prepare("SELECT * FROM Kebab");
        $stmt->execute();
        $result = $stmt->get_result();

        $kebabs = [];
        while ($row = $result->fetch_assoc()) {
            $kebab = $row;
            // Obtener los ingredientes asociados al kebab
            $stmtIngredientes = $this->conexion->prepare("
                SELECT Ingredientes.* FROM Ingredientes
                INNER JOIN Kebab_has_Ingredientes ON Ingredientes.idIngredientes = Kebab_has_Ingredientes.Ingredientes_idIngredientes
                WHERE Kebab_has_Ingredientes.Kebab_idKebab = ?
            ");
            $stmtIngredientes->bind_param("i", $kebab['idKebab']);
            $stmtIngredientes->execute();
            $resultIngredientes = $stmtIngredientes->get_result();

            $ingredientes = [];
            while ($rowIngrediente = $resultIngredientes->fetch_assoc()) {
                $ingredientes[] = $rowIngrediente;
            }
            $kebab['ingredientes'] = $ingredientes;

            $kebabs[] = $kebab;
        }

        return $kebabs;
    }

    // Obtener un kebab por ID con sus ingredientes
    public function getKebabById($idKebab) {
        $stmt = $this->conexion->prepare("SELECT * FROM Kebab WHERE idKebab = ?");
        $stmt->bind_param("i", $idKebab);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            return null;
        }

        $kebab = $result->fetch_assoc();

        // Obtener los ingredientes asociados al kebab
        $stmtIngredientes = $this->conexion->prepare("
            SELECT Ingredientes.* FROM Ingredientes
            INNER JOIN Kebab_has_Ingredientes ON Ingredientes.idIngredientes = Kebab_has_Ingredientes.Ingredientes_idIngredientes
            WHERE Kebab_has_Ingredientes.Kebab_idKebab = ?
        ");
        $stmtIngredientes->bind_param("i", $idKebab);
        $stmtIngredientes->execute();
        $resultIngredientes = $stmtIngredientes->get_result();

        $ingredientes = [];
        while ($row = $resultIngredientes->fetch_assoc()) {
            $ingredientes[] = $row;
        }
        $kebab['ingredientes'] = $ingredientes;

        return $kebab;
    }
}
?>
