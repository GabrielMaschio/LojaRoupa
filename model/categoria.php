<?php
class Categoria {
    public $id;
    public $nome_categoria;

    public function __construct($id, $nome_categoria) {
        $this->id = $id;
        $this->nome_categoria = $nome_categoria;
    }
}