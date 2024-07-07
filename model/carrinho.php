<?php
class Carrinho {
    public $id;
    public $id_cliente;
    public $nome_produto;
    public $quantidade;
    public $valor; 
    public $desconto; 
    public $nome_categoria;
    public $nome_tipo;
    public $pp;

    public function __construct($id, $id_cliente, $nome_produto, $quantidade, $valor, $desconto, $nome_categoria, $pp) {
        $this->id = $id;
        $this->id_cliente = $id_cliente;
        $this->nome_produto = $nome_produto;
        $this->quantidade = $quantidade;
        $this->valor = $valor;
        $this->desconto = $desconto;
        $this->nome_categoria = $nome_categoria;
        $this->pp = $pp;
    }
}