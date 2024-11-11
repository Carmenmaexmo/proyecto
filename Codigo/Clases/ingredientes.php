<?php
class Ingredientes {
    private $idIngrediente;
    private $nombre;
    private $foto;
    private $precio;
    private $tipo;
    private $alergenos=[];

    // Constructor
    public function __construct($idIngrediente, $nombre, $foto, $precio, $tipo, $alergenos = []) {
        $this->idIngrediente = $idIngrediente;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->precio = $precio;
        $this->tipo = $tipo;
        $this->alergenos = $alergenos;
    }

    //Metodo para agregar alergenos
    public function addAlergenos(Alergenos $alergenos) {
        $this->alergenos[] = $alergenos;
    }

    // Getters
    public function getIdIngrediente() {
        return $this->idIngrediente;
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

    public function getTipo() {
        return $this->tipo;
    }

    public function getAlergenos() {
        return $this->alergenos;
    }

    // Setters
    public function setIdIngrediente($idIngrediente) {
        $this->idIngrediente = $idIngrediente;
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

    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    public function setAlergenos(Alergenos $alergenos) {
        $this->alergenos = $alergenos;
    }
}
