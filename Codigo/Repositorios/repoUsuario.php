<?php
require_once 'conexion.php';
require_once '../Clases/Usuario.php';
require_once '../Clases/Alergenos.php';

class RepoUsuario {
    private $conexion;

    // Constructor que recibe la conexión de la base de datos
    public function __construct($db) {
        $this->conexion = $db->getConnection();  // Obtener la conexión de DB
    }

    // Método para crear un usuario y asociar sus alérgenos
    public function createUsuario($usuarioData, $alergenos) {
        // Iniciar una transacción
        $this->conexion->begin_transaction();

        // Convertir el carrito a JSON
        $carritoJSON = json_encode($usuarioData['carrito']);

        // Insertar el usuario en la tabla Usuario
        $stmt = $this->conexion->prepare("INSERT INTO Usuario (nombre, ubicacion, telefono, contraseña, foto, monedero, carrito, rol) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param("sssssis", 
            $usuarioData['nombre'],
            $usuarioData['ubicacion'],
            $usuarioData['telefono'],
            $usuarioData['contraseña'],
            $usuarioData['foto'],
            $usuarioData['monedero'],
            $carritoJSON,
            $usuarioData['rol']
        );
        
        $stmt->execute();

        // Verificar si el usuario fue insertado correctamente
        if ($stmt->affected_rows === 0) {
            // Si no se insertó el usuario, hacer rollback
            $this->conexion->rollback();
            echo json_encode(['error' => 'No se pudo insertar el usuario.']);
            return null;
        }

        // Obtener el ID del usuario recién insertado
        $usuarioId = $this->conexion->insert_id;
        echo "Usuario creado con ID: $usuarioId\n";  // Depuración

        // Asociar los alérgenos al usuario en la tabla intermedia Usuario_has_Alergenos
        $stmtAlergenos = $this->conexion->prepare("INSERT INTO Usuario_has_Alergenos (Usuario_idUsuario, Alergenos_idAlergenos) VALUES (?, ?)");
        foreach ($alergenos as $alergenoId) {
            $stmtAlergenos->bind_param("ii", $usuarioId, $alergenoId);
            $stmtAlergenos->execute();

            // Verificar si el alérgeno fue insertado correctamente
            if ($stmtAlergenos->affected_rows === 0) {
                // Si no se insertó el alérgeno, hacer rollback
                $this->conexion->rollback();
                echo json_encode(['error' => 'No se pudo insertar el alérgeno.']);
                return null;
            }
            echo "Alérgeno $alergenoId asociado con el usuario $usuarioId\n"; // Depuración
        }

        // Confirmar la transacción
        $this->conexion->commit();

        return $usuarioId;
    }
}