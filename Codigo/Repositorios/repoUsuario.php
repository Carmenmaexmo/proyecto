<?php
require_once 'Conexion.php';
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

        // Asignar las variables primero
        $nombre = $usuarioData['nombre'];
        $ubicacion = isset($usuarioData['ubicacion']) && $usuarioData['ubicacion'] != "" ? $usuarioData['ubicacion'] : null;
        $telefono = $usuarioData['telefono'];
        $contraseña = $usuarioData['contraseña'];
        $foto = isset($usuarioData['foto']) && $usuarioData['foto'] != "" ? $usuarioData['foto'] : null;
        $monedero = $usuarioData['monedero'];
        $correo = $usuarioData['correo'];
        $rol = $usuarioData['rol'];
        $carritoJSON = isset($usuarioData['carrito']) ? json_encode($usuarioData['carrito']) : null;

        // Insertar el usuario en la tabla Usuario
        $stmt = $this->conexion->prepare("INSERT INTO Usuario (nombre, ubicacion, telefono, contraseña, foto, monedero, carrito, rol, correo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssisss", 
            $nombre,
            $ubicacion,
            $telefono,
            $contraseña,
            $foto,
            $monedero,
            $carritoJSON,
            $rol,
            $correo
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
    // Método para actualizar un usuario y sus alérgenos
    public function updateUsuario($usuarioId, $usuarioData, $alergenos) {
        // Iniciar una transacción
        $this->conexion->begin_transaction();

        // Actualizar los datos del usuario en la tabla Usuario
        $stmt = $this->conexion->prepare("UPDATE Usuario SET nombre = ?, ubicacion = ?, telefono = ?, contraseña = ?, foto = ?, monedero = ?, carrito = ?, rol = ? , correo = ? WHERE idUsuario = ?");
        $carritoJSON = json_encode($usuarioData['carrito']);
        $stmt->bind_param("sssssissi", 
            $usuarioData['nombre'],
            $usuarioData['ubicacion'],
            $usuarioData['telefono'],
            $usuarioData['contraseña'],
            $usuarioData['foto'],
            $usuarioData['monedero'],
            $carritoJSON,
            $usuarioData['rol'],
            $usuarioData['correo'],
            $usuarioId
        );

        $stmt->execute();

        // Verificar si el usuario fue actualizado correctamente
        if ($stmt->affected_rows === 0) {
            // Si no se actualizó, hacer rollback
            $this->conexion->rollback();
            echo json_encode(['error' => 'No se pudo actualizar el usuario.']);
            return null;
        }

        // Eliminar los alérgenos anteriores asociados a este usuario
        $stmtEliminar = $this->conexion->prepare("DELETE FROM Usuario_has_Alergenos WHERE Usuario_idUsuario = ?");
        $stmtEliminar->bind_param("i", $usuarioId);
        $stmtEliminar->execute();

        // Asociar los nuevos alérgenos al usuario
        $stmtAlergenos = $this->conexion->prepare("INSERT INTO Usuario_has_Alergenos (Usuario_idUsuario, Alergenos_idAlergenos) VALUES (?, ?)");
        foreach ($alergenos as $alergenoId) {
            $stmtAlergenos->bind_param("ii", $usuarioId, $alergenoId);
            $stmtAlergenos->execute();
        }

        // Confirmar la transacción
        $this->conexion->commit();

        return $usuarioId;
    }

    // Método para obtener un usuario por su ID
    public function getUsuarioById($usuarioId) {
        $stmt = $this->conexion->prepare("SELECT * FROM Usuario WHERE idUsuario = ?");
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            echo json_encode(['error' => 'Usuario no encontrado.']);
            return null;
        }

        $usuario = $result->fetch_assoc();

        // Obtener los alérgenos del usuario
        $stmtAlergenos = $this->conexion->prepare("SELECT Alergenos_idAlergenos FROM Usuario_has_Alergenos WHERE Usuario_idUsuario = ?");
        $stmtAlergenos->bind_param("i", $usuarioId);
        $stmtAlergenos->execute();
        $resultAlergenos = $stmtAlergenos->get_result();

        $alergenos = [];
        while ($row = $resultAlergenos->fetch_assoc()) {
            $alergenos[] = $row['Alergenos_idAlergenos'];
        }

        $usuario['alergenos'] = $alergenos;

        return $usuario;
    }

    // Método para obtener todos los usuarios
    public function getAllUsuarios() {
        $stmt = $this->conexion->prepare("SELECT * FROM Usuario");
        $stmt->execute();
        $result = $stmt->get_result();

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            // Consultar los alérgenos de cada usuario
            $stmtAlergenos = $this->conexion->prepare("SELECT Alergenos_idAlergenos FROM Usuario_has_Alergenos WHERE Usuario_idUsuario = ?");
            $stmtAlergenos->bind_param("i", $row['idUsuario']);
            $stmtAlergenos->execute();
            $resultAlergenos = $stmtAlergenos->get_result();

            $alergenos = [];
            while ($alergeno = $resultAlergenos->fetch_assoc()) {
                $alergenos[] = $alergeno['Alergenos_idAlergenos'];
            }

            $row['alergenos'] = $alergenos;

            $usuarios[] = $row;
        }

        return $usuarios;
    }

    // Método para eliminar un usuario
    public function deleteUsuario($usuarioId) {
        $this->conexion->begin_transaction();

        // Eliminar los alérgenos asociados
        $stmtAlergenos = $this->conexion->prepare("DELETE FROM Usuario_has_Alergenos WHERE Usuario_idUsuario = ?");
        $stmtAlergenos->bind_param("i", $usuarioId);
        $stmtAlergenos->execute();

        // Eliminar el usuario
        $stmt = $this->conexion->prepare("DELETE FROM Usuario WHERE idUsuario = ?");
        $stmt->bind_param("i", $usuarioId);
        $stmt->execute();

        if ($stmt->affected_rows === 0) {
            $this->conexion->rollback();
            echo json_encode(['error' => 'No se pudo eliminar el usuario.']);
            return false;
        }

        $this->conexion->commit();

        return true;
    }
}
