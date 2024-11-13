<?php

class LineaDePedido {
    private $idLineaDePedido;
    private $cantidad;
    private $precio;
    private $lineaPedido; // Este campo contendrá el JSON
    private $pedido;
    private $kebab;

    public function __construct($idLineaDePedido, $cantidad, $precio, Kebab $kebab, Pedidos $pedido) {
        $this->idLineaDePedido = $idLineaDePedido;
        $this->cantidad = $cantidad;
        $this->precio = $precio;
        $this->pedido = $pedido->getIdPedidos(); 
        $this->lineaPedido = $this->crearLineaPedidoJSON($kebab->getIdKebab(),$kebab->getNombre(), $kebab->getIngredientes() , $cantidad, $kebab->getPrecio());
    }

    //Metodo para agregar kebab
    public function addKebab(Kebab $kebab) {
        $this->kebab[] = $kebab;
    }

    // Método para crear el JSON
    private function crearLineaPedidoJSON(Kebab $kebab, $cantidad) {
        $data = [
            'idKebab' => $kebab->getIdKebab(),
            'nombreKebab' => $kebab->getNombre(),
            'ingredientes' => $kebab->getIngredientes(),
            'cantidad' => $cantidad,
            'precio' => $kebab->getPrecio()
        ];
        return json_encode($data);
    }

    // Getters
    public function getIdLineaDePedido() {
        return $this->idLineaDePedido;
    }

    public function getCantidad() {
        return $this->cantidad;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getLineaPedido() {
        return $this->lineaPedido;
    }

    public function getPedido() {
        return $this->pedido;
    } 

    public function getKebab() {
        return $this->kebab;
    }

    // Setters

    public function setIdLineaDePedido($idLineaDePedido) {
        $this->idLineaDePedido = $idLineaDePedido;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
    }

    public function setPrecio($precio) {        
        $this->precio = $precio;
    }

    public function setPedido(Pedidos $pedido) {
        $this->pedido = $pedido;
    }

    public function setKebab(Kebab $kebab) {
        $this->kebab = $kebab;
        $this->lineaPedido = $this->crearLineaPedidoJSON($kebab->getIdKebab(),$kebab->getNombre(), $this->cantidad, $kebab->getPrecio());
    }

}

