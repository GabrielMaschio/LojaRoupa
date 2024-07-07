<?php
    include '../../model/produto.php';
    include '../../Config/Config.php';

    if ($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro na conexão com o banco de dados: " . $conn->connect_error];
        echo json_encode($retorna);
        exit();
    }

    $query = "SELECT codproduto AS id, nomeproduto as nome_produto, quantidade, valor, desconto, nome_categoria, 
                     codcategoria_fk as id_categoria, nometipo as nome_tipo, codtipo_fk AS id_tipo, pp
              FROM produto, tipo, categoria
              WHERE codtipo_fk = codtipo AND codcategoria_fk = codcategoria
              ORDER BY id ASC";
    $result = $conn->query($query);
    
    $produtos = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $produto = new Produto($row["id"], $row["nome_produto"], $row["quantidade"], $row["valor"], $row["desconto"], 
                                   $row["nome_categoria"], $row["id_categoria"], $row["nome_tipo"], $row["id_tipo"], $row["pp"]);
            $produtos[] = $produto;
        }
    } 

    echo json_encode($produtos);

    $conn->close();