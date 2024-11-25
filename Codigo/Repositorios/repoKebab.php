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
        // Actualizar los datos del kebab en la base de datos
        $stmt = $this->conexion->prepare("UPDATE Kebab SET nombre = ?, foto = ?, precio = ?, descripcion = ? WHERE idKebab = ?");
        $stmt->bind_param("ssdsi", $kebabData['nombre'], $kebabData['foto'], $kebabData['precio'], $kebabData['descripcion'], $idKebab);
        $stmt->execute();
    
        // Verificar si la actualización fue exitosa
        if ($stmt->affected_rows > 0) {
            // Eliminar los ingredientes previos del kebab (si es necesario)
            $this->removeIngredientesFromKebab($idKebab);
    
            // Agregar los nuevos ingredientes
            foreach ($ingredientesIds as $ingredienteId) {
                // Aquí se agrega el ingrediente al kebab
                $this->addIngredienteToKebab($idKebab, $ingredienteId);
            }
    
            return true;
        }
    
        return false;
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
    public function addIngredienteToKebab($idKebab, $ingredienteId) {
        $stmt = $this->conexion->prepare("INSERT INTO Kebab_has_Ingredientes (Kebab_idKebab, Ingredientes_idIngredientes) VALUES (?, ?)");
        $stmt->bind_param("ii", $idKebab, $ingredienteId);
        $stmt->execute();
    }
    
    // Eliminar asociaciones de ingredientes de un kebab
    public function removeIngredientesFromKebab($idKebab) {
        $stmt = $this->conexion->prepare("DELETE FROM Kebab_has_Ingredientes WHERE Kebab_idKebab = ?");
        $stmt->bind_param("i", $idKebab);
        $stmt->execute();
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
