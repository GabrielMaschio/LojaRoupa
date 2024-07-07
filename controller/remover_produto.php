<?php
    include '../Config/Config.php';

    if($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro na conexão com o banco de dados: " . $conn->connect_error];
        echo json_encode($retorna);
        exit();
    }

    $id_cliente = $_POST["id_cliente"];
    $id_produto = $_POST["id_produto"];

    $query = "DELETE FROM carrinho WHERE codcliente_fk = ? AND codproduto_fk = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_cliente, $id_produto);
    
    if($stmt->execute()) {
        $retorna = ['status' => true, 'msg' => "Produto removido com sucesso"];
    } else {
        $retorna = ['status' => false, 'msg' => "Erro ao remover produto"];
    }
    
    echo json_encode($retorna);
    $stmt->close();
    $conn->close();