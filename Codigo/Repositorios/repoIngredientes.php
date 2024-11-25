<?php
require_once 'Conexion.php';
require_once '../Clases/Ingredientes.php';
require_once '../Clases/Alergenos.php';

class RepoIngredientes {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db->getConnection();
    }

    public function createIngrediente($ingredienteData, $alergenosIds = []) {
        $stmt = $this->conexion->prepare("INSERT INTO Ingredientes (nombre, foto, precio, tipo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $ingredienteData['nombre'], $ingredienteData['foto'], $ingredienteData['precio'], $ingredienteData['tipo']);
        $stmt->execute();
    
        if ($stmt->affected_rows === 0) {
            return null;
        }
    
        $ingredienteId = $this->conexion->insert_id;
    
        // Asociar los alérgenos seleccionados
        if (!empty($alergenosIds)) {
            foreach ($alergenosIds as $alergenoId) {
                $this->addAlergenoToIngrediente($ingredienteId, $alergenoId);
            }
        }
    
        return $ingredienteId;
    }
    
    

    public function updateIngrediente($idIngrediente, $ingredienteData, $alergenosIds = []) {
        $stmt = $this->conexion->prepare("UPDATE Ingredientes SET nombre = ?, foto = ?, precio = ?, tipo = ? WHERE idIngredientes = ?");
        $stmt->bind_param("ssdsi", $ingredienteData['nombre'], $ingredienteData['foto'], $ingredienteData['precio'], $ingredienteData['tipo'], $idIngrediente);
        $stmt->execute();
    
        $this->removeAlergenosFromIngrediente($idIngrediente);
        foreach ($alergenosIds as $alergenoId) {
            $this->addAlergenoToIngrediente($idIngrediente, $alergenoId);
        }
    
        return $stmt->affected_rows > 0;
    }
    

    // Función para eliminar los alérgenos de un ingrediente
    public function deleteAlergenosFromIngrediente($idIngrediente) {
        $query = "DELETE FROM ingredientes_alergenos WHERE idIngrediente = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param("i", $idIngrediente);
        $stmt->execute();
    }

    // Eliminar un ingrediente
    public function deleteIngrediente($idIngrediente) {
        $this->removeAlergenosFromIngrediente($idIngrediente);

        $stmt = $this->conexion->prepare("DELETE FROM Ingredientes WHERE idIngredientes = ?");
        $stmt->bind_param("i", $idIngrediente);
        $stmt->execute();
        echo "llego aqui";

        return $stmt->affected_rows > 0;
    }

    // Asociar un alérgeno a un ingrediente
    public function addAlergenoToIngrediente($ingredienteId, $alergenoId) {
        $stmt = $this->conexion->prepare("INSERT INTO Ingredientes_has_Alergenos (Ingredientes_idIngredientes, Alergenos_idAlergenos) VALUES (?, ?)");
        $stmt->bind_param("ii", $ingredienteId, $alergenoId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Eliminar asociaciones de alérgenos de un ingrediente
    public function removeAlergenosFromIngrediente($ingredienteId) {
        $stmt = $this->conexion->prepare("DELETE FROM Ingredientes_has_Alergenos WHERE Ingredientes_idIngredientes = ?");
        $stmt->bind_param("i", $ingredienteId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Obtener todos los ingredientes con sus alérgenos
    public function getAllIngredientes() {
        $stmt = $this->conexion->prepare("SELECT * FROM Ingredientes");
        $stmt->execute();
        $result = $stmt->get_result();

        $ingredientes = [];

        // Recuperar todos los ingredientes
        while ($row = $result->fetch_assoc()) {
            // Consultar los alérgenos asociados a este ingrediente
            $stmtAlergenos = $this->conexion->prepare("
                SELECT Alergenos.* FROM Alergenos
                INNER JOIN Ingredientes_has_Alergenos ON Alergenos.idAlergenos = Ingredientes_has_Alergenos.Alergenos_idAlergenos
                WHERE Ingredientes_has_Alergenos.Ingredientes_idIngredientes = ?
            ");
            $stmtAlergenos->bind_param("i", $row['idIngredientes']); // Asumiendo que 'idIngredientes' es el nombre de la columna ID
            $stmtAlergenos->execute();
            $resultAlergenos = $stmtAlergenos->get_result();

            $alergenos = [];
            while ($alergeno = $resultAlergenos->fetch_assoc()) {
                $alergenos[] = $alergeno; // Agregar alérgenos a la lista
            }

            // Añadir alérgenos al ingrediente
            $row['alergenos'] = $alergenos;

            // Añadir el ingrediente con los alérgenos a la lista final
            $ingredientes[] = $row;
        }

        return $ingredientes;
    }

    public function getIngredienteById($idIngrediente) {
        $stmt = $this->conexion->prepare("SELECT * FROM Ingredientes WHERE idIngredientes = ?");
        $stmt->bind_param("i", $idIngrediente);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows === 0) {
            return null;
        }
    
        $ingrediente = $result->fetch_assoc();
    
        // Obtener los alérgenos asociados
        $stmtAlergenos = $this->conexion->prepare("
            SELECT Alergenos.* FROM Alergenos
            INNER JOIN Ingredientes_has_Alergenos ON Alergenos.idAlergenos = Ingredientes_has_Alergenos.Alergenos_idAlergenos
            WHERE Ingredientes_has_Alergenos.Ingredientes_idIngredientes = ?
        ");
        $stmtAlergenos->bind_param("i", $idIngrediente);
        $stmtAlergenos->execute();
        $resultAlergenos = $stmtAlergenos->get_result();
    
        $alergenos = [];
        while ($row = $resultAlergenos->fetch_assoc()) {
            $alergenos[] = $row;
        }
    
        $ingrediente['alergenos'] = $alergenos;
    
        return $ingrediente;
    }
    

}
