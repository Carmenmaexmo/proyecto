<?php

class Pedidos {
    private $idPedidos;
    private $estado;
    private $fechaHora;
    private $precioTotal;
    private $usuario;

    public function __construct($idPedidos, $estado, $fechaHora, $precioTotal, $usuario) {
        $this->idPedidos = $idPedidos;
        $this->estado = $estado;
        $this->fechaHora = $fechaHora;
        $this->precioTotal = $precioTotal;
        $this->usuario = $usuario->getIdUsuario();
    }

    //Metodo para agregar usuario
    public function addUsuario(Usuario $usuario) {
        $this->usuario[] = $usuario;
    }

    // Getters and Setters
    public function getIdPedidos() {
        return $this->idPedidos;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function getFechaHora() {
        return $this->fechaHora;
    }

    public function getPrecioTotal() {
        return $this->precioTotal;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function setFechaHora($fechaHora) {
        $this->fechaHora = $fechaHora;
    }

    public function setPrecioTotal($precioTotal) {
        $this->precioTotal = $precioTotal;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }
}
