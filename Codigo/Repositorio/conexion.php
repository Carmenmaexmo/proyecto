<?php
class Conexion {
    private $host = 'localhost'; // Servidor MySQL
    private $usuario = 'root';   // Usuario de MySQL
    private $contrasena = 'root';    // Contraseña de MySQL
    private $base_de_datos = 'proyecto'; // Nombre de la base de datos
    private $conexion; // Almacena la conexión

    // Constructor de la clase: intentamos conectar a la base de datos
    public function __construct() {
        $this->conn();
    }

    // Método para conectar a la base de datos
    private function conn() {
        // Crear una conexión utilizando la extensión mysqli
        $this->conexion = new mysqli($this->host, $this->usuario, $this->contrasena, $this->base_de_datos);

        // Verificar si la conexión fue exitosa
        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }
}