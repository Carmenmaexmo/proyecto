<?php
require_once 'Conexion.php';
require_once '../Clases/Alergenos.php';
require_once '../Clases/Ingredientes.php';
require_once '../Clases/Usuario.php';

class RepoAlergenos {
    private $conexion;

    public function __construct($db) {
        $this->conexion = $db->getConnection();
    }

    // Crear un alérgeno
    public function createAlergeno($alergenoData) {
        $stmt = $this->conexion->prepare("INSERT INTO Alergenos (nombre, foto, descripcion) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $alergenoData['nombre'], $alergenoData['foto'], $alergenoData['descripcion']);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            return null;
        }

        return $this->conexion->insert_id; // Devuelve el ID del alérgeno creado
    }

    // Actualizar un alérgeno
    public function updateAlergeno($idAlergeno, $alergenoData) {
        $stmt = $this->conexion->prepare("UPDATE Alergenos SET nombre = ?, foto = ?, descripcion = ? WHERE idAlergenos = ?");
        $stmt->bind_param("sssi", $alergenoData['nombre'], $alergenoData['foto'], $alergenoData['descripcion'], $idAlergeno);
        $stmt->execute();

        return $stmt->affected_rows > 0; // Devuelve true si la actualización fue exitosa
    }

    // Asociar un usuario a un alérgeno en Usuario_has_Alergenos
    public function addUsuarioToAlergeno($alergenoId, $usuarioId) {
        $stmt = $this->conexion->prepare("INSERT INTO Usuario_has_Alergenos (Alergenos_idAlergenos, Usuario_idUsuario) VALUES (?, ?)");
        $stmt->bind_param("ii", $alergenoId, $usuarioId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Eliminar la asociación de un usuario de un alérgeno
    public function removeUsuarioFromAlergeno($alergenoId, $usuarioId) {
        $stmt = $this->conexion->prepare("DELETE FROM Usuario_has_Alergenos WHERE Alergenos_idAlergenos = ? AND Usuario_idUsuario = ?");
        $stmt->bind_param("ii", $alergenoId, $usuarioId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Asociar un ingrediente a un alérgeno en Ingredientes_has_Alergenos
    public function addIngredienteToAlergeno($alergenoId, $ingredienteId) {
        $stmt = $this->conexion->prepare("INSERT INTO Ingredientes_has_Alergenos (Alergenos_idAlergenos, Ingredientes_idIngredientes) VALUES (?, ?)");
        $stmt->bind_param("ii", $alergenoId, $ingredienteId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Eliminar la asociación de un ingrediente de un alérgeno
    public function removeIngredienteFromAlergeno($alergenoId, $ingredienteId) {
        $stmt = $this->conexion->prepare("DELETE FROM Ingredientes_has_Alergenos WHERE Alergenos_idAlergenos = ? AND Ingredientes_idIngredientes = ?");
        $stmt->bind_param("ii", $alergenoId, $ingredienteId);
        $stmt->execute();

        return $stmt->affected_rows > 0;
    }

    // Obtener todos los alérgenos
    public function getAllAlergenos() {
        $stmt = $this->conexion->prepare("SELECT * FROM Alergenos");
        $stmt->execute();
        $result = $stmt->get_result();

        $alergenos = [];
        while ($row = $result->fetch_assoc()) {
            $alergenos[] = $row;
        }

        return $alergenos;
    }

    // Obtener un alérgeno por ID con sus usuarios e ingredientes asociados
    public function getAlergenoById($idAlergeno) {
        $stmt = $this->conexion->prepare("SELECT * FROM Alergenos WHERE idAlergenos = ?");
        $stmt->bind_param("i", $idAlergeno);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }

        $alergeno = $result->fetch_assoc();
        
        // Obtener usuarios asociados al alérgeno
        $stmtUsuarios = $this->conexion->prepare("
            SELECT Usuario.* FROM Usuario
            INNER JOIN Usuario_has_Alergenos ON Usuario.idUsuario = Usuario_has_Alergenos.Usuario_idUsuario
            WHERE Usuario_has_Alergenos.Alergenos_idAlergenos = ?
        ");
        $stmtUsuarios->bind_param("i", $idAlergeno);
        $stmtUsuarios->execute();
        $resultUsuarios = $stmtUsuarios->get_result();
        $usuarios = [];
        while ($row = $resultUsuarios->fetch_assoc()) {
            $usuarios[] = $row;
        }
        $alergeno['usuarios'] = $usuarios;

        // Obtener ingredientes asociados al alérgeno
        $stmtIngredientes = $this->conexion->prepare("
            SELECT Ingredientes.* FROM Ingredientes
            INNER JOIN Ingredientes_has_Alergenos ON Ingredientes.idIngredientes = Ingredientes_has_Alergenos.Ingredientes_idIngredientes
            WHERE Ingredientes_has_Alergenos.Alergenos_idAlergenos = ?
        ");
        $stmtIngredientes->bind_param("i", $idAlergeno);
        $stmtIngredientes->execute();
        $resultIngredientes = $stmtIngredientes->get_result();
        $ingredientes = [];
        while ($row = $resultIngredientes->fetch_assoc()) {
            $ingredientes[] = $row;
        }
        $alergeno['ingredientes'] = $ingredientes;

        return $alergeno;
    }

    // Eliminar un alérgeno
    public function deleteAlergeno($idAlergeno) {
    // Primero, eliminamos las asociaciones en las tablas intermedias
    $stmt1 = $this->conexion->prepare("DELETE FROM Usuario_has_Alergenos WHERE Alergenos_idAlergenos = ?");
    $stmt1->bind_param("i", $idAlergeno);
    $stmt1->execute();

    // Eliminar las asociaciones en Ingredientes_has_Alergenos
    $stmt2 = $this->conexion->prepare("DELETE FROM Ingredientes_has_Alergenos WHERE Alergenos_idAlergenos = ?");
    $stmt2->bind_param("i", $idAlergeno);
    $stmt2->execute();

    // Ahora, eliminamos el alérgeno de la tabla Alergenos
    $stmt3 = $this->conexion->prepare("DELETE FROM Alergenos WHERE idAlergenos = ?");
    $stmt3->bind_param("i", $idAlergeno);
    $stmt3->execute();

    // Comprobamos si se eliminó correctamente
    return $stmt3->affected_rows > 0;
    }

}