<?php
class Produto {
    public $id;
    public $nome_produto;
    public $quantidade;
    public $valor; 
    public $desconto; 
    public $nome_categoria;
    public $id_categoria;
    public $nome_tipo;
    public $id_tipo;
    public $pp;

    public function __construct($id, $nome_produto, $quantidade, $valor, $desconto, $nome_categoria, $id_categoria, $nome_tipo, $id_tipo, $pp) {
        $this->id = $id;
        $this->nome_produto = $nome_produto;
        $this->quantidade = $quantidade;
        $this->valor = $valor;
        $this->desconto = $desconto;
        $this->nome_categoria = $nome_categoria;
        $this->id_categoria = $id_categoria;
        $this->nome_tipo = $nome_tipo;
        $this->id_tipo = $id_tipo;
        $this->pp = $pp;
    }
}