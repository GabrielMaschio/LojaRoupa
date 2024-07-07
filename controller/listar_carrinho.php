<?php
    session_start();
    include '../model/carrinho.php';
    include '../Config/Config.php';

    if ($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro na conexão com o banco de dados: " . $conn->connect_error];
        echo json_encode($retorna);
        exit();
    }

    $id = $_SESSION["id"];

    $query = "SELECT p.codproduto AS id, p.nomeproduto AS nome_produto, ca.quantidade AS quantidade, ca.valor_unit AS valor, 
                     p.desconto, c.nome_categoria, p.pp
              FROM produto AS p
              INNER JOIN categoria AS c ON p.codcategoria_fk = c.codcategoria
              INNER JOIN carrinho AS ca ON p.codproduto = ca.codproduto_fk
              WHERE ca.codcliente_fk = ?
              ORDER BY id ASC";
    $stmt = $conn->prepare($query);

    // Verifica se a preparação da consulta foi bem-sucedida
    if ($stmt === false) {
        $retorna = ['status' => false, 'msg' => "Erro na preparação da consulta: " . $conn->error];
        echo json_encode($retorna);
        exit();
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $produtos = array();
        while ($row = $result->fetch_assoc()) {
            $produto = new Carrinho($row["id"], $id, $row["nome_produto"], $row["quantidade"], $row["valor"], $row["desconto"], 
                                    $row["nome_categoria"], $row["pp"]);
            $produtos[] = $produto;
        }
        echo json_encode($produtos);
        exit();
    } else {
        $retorna = ['status' => false, 'msg' => "Nenhum produto encontrado no carrinho"];
    }

    echo json_encode($retorna);
    $stmt->close();
    $conn->close();