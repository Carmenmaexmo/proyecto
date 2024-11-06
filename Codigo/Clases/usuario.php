<?php
// classes/User.php

require_once 'config/db.php';

class Usuario {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    
    // Crear un nuevo usuario
    public function create($data) {
        $query = "INSERT INTO usuario (nombre, ubicacion, telefono, contraseña, direccion, monedero, carrito)
                  VALUES (:nombre, :ubicacion, :telefono, :contraseña, :direccion, :monedero, :carrito)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':ubicacion', $data['ubicacion']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':contraseña', password_hash($data['contraseña'], PASSWORD_DEFAULT));
        $stmt->bindParam(':direccion', $data['direccion']);
        $stmt->bindParam(':monedero', $data['monedero']);
        $stmt->bindParam(':carrito', json_encode($data['carrito']));
        $stmt->execute();
    }

    // Login del usuario
    public function login($data) {
        $query = "SELECT * FROM usuario WHERE nombre = :nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data['contraseña'], $user['contraseña'])) {
            return $user;  // Devuelve el usuario para luego generar el token
        }
        return false;
    }

    // Obtener un usuario por ID
    public function getById($id) {
        $query = "SELECT * FROM usuario WHERE idUsuario = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

