<?php

class Direccion {
    private $idDireccion;
    private $direccion;
    private $estado;
    private $usuario;

    public function __construct($idDireccion, $direccion, $estado, Usuario $usuario) {
        $this->idDireccion = $idDireccion;
        $this->direccion = $direccion;
        $this->estado = $estado;
        $this->usuario = $usuario->getidUsuario();
    }

    // Getters
    public function getIdDireccion() {
        return $this->idDireccion;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    // Setters
    public function setIdDireccion($idDireccion) {
        $this->idDireccion = $idDireccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }
}