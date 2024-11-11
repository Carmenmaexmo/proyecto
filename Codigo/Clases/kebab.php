<?php
class Kebab {
    private $idKebab;
    private $nombre;
    private $foto;
    private $precio;
    private $descripcion;
    private $ingredientes =[];

    // Constructor
    public function __construct($idKebab, $nombre, $foto, $precio, $descripcion, $ingredientes=[]) {
        $this->idKebab = $idKebab;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->precio = $precio;
        $this->descripcion = $descripcion;
        $this->ingredientes = $ingredientes;
    }

    //Metodo para agregar ingredientes
    public function addIngredientes(Ingredientes $ingredientes) {
        $this->ingredientes[] = $ingredientes;
    }

    // Getters
    public function getIdKebab() {
        return $this->idKebab;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getIngredientes() {
        return $this->ingredientes;
    }

    // Setters
    public function setIdKebab($idKebab) {
        $this->idKebab = $idKebab;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setIngredientes(Ingredientes $ingredientes) {
        $this->ingredientes = $ingredientes;
    }

}
