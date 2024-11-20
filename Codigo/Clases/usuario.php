<?php

class Usuario {
    private $idUsuario;
    private $nombre;
    private $ubicacion;
    private $telefono;
    private $contraseña;
    private $foto;
    private $monedero;
    private $carrito;
    private $rol;
    private $correo;
    private $alergenos=[];

    public function __construct($idUsuario, $nombre, $ubicacion, $telefono, $contraseña, $foto, $monedero, $carrito, $rol, $correo, $alergenos = []) {
        $this->idUsuario = $idUsuario;
        $this->nombre = $nombre;
        $this->ubicacion = $ubicacion;
        $this->telefono = $telefono;
        $this->contraseña = $contraseña;
        $this->foto = $foto;
        $this->monedero = $monedero;
        $this->carrito = $carrito;
        $this->rol = $rol;
        $this->correo = $correo;
        $this->alergenos = $alergenos;
    }

    // Getters
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getUbicacion() {
        return $this->ubicacion;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getContraseña() {
        return $this->contraseña;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function getMonedero() {
        return $this->monedero;
    }

    public function getCarrito() {
        return $this->carrito;
    }

    public function getAlergenos() {
        return $this->alergenos;
    }

    public function getRol() {
        return $this->rol;
    }

    public function getCorreo() {
        return $this->correo;
    }

      //Metodo para agregar alergenos
      public function addAlergenos(Alergenos $alergenos) {
        $this->alergenos[] = $alergenos;
        // También añadimos al usuario al alérgeno
        $alergenos->addUsuario($this);
    }

    // Setters

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setUbicacion($ubicacion) {
        $this->ubicacion = $ubicacion;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setContraseña($contraseña) {
        $this->contraseña = $contraseña;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }

    public function setMonedero($monedero) {
        $this->monedero = $monedero;
    }

    public function setCarrito($carrito) {
        $this->carrito = $carrito;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }

    public function setAlergenos(Alergenos $alergenos) {
        $this->alergenos = $alergenos;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }
   
}
