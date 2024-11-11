<?php
class Alergenos {
    private $idAlergenos;
    private $nombre;
    private $foto;
    private $descripcion;
    private $ingredientes =[];
    private $usuario =[];

    // Constructor
    public function __construct($idAlergenos, $nombre, $foto, $descripcion, $ingredientes = [], $usuario = []) {
        $this->idAlergenos = $idAlergenos;
        $this->nombre = $nombre;
        $this->foto = $foto;
        $this->descripcion = $descripcion;
        $this->ingredientes = $ingredientes;
        $this->usuario = $usuario;
    }

    //Metodo para agregar ingredientes
    public function addIngredientes(Ingredientes $ingredientes) {
        $this->ingredientes[] = $ingredientes;
    }  

    //Metodo para agregar usuario
    public function addUsuario(Usuario $usuario) {
        $this->usuario[] = $usuario;
        // También añadimos el alérgeno al usuario
        $usuario->addAlergenos($this);
    }

    // Getters
    public function getIdAlergenos() {
        return $this->idAlergenos;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getIngredientes() {
        return $this->ingredientes;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    // Setters
    public function setIdAlergenos($idAlergenos) {
        $this->idAlergenos = $idAlergenos;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setIngredientes(Ingredientes $ingredientes) {
        $this->ingredientes = $ingredientes;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }

}
