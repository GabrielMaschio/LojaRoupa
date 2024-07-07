<?php
    session_start();
    include '../Config/Config.php';

    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        $retorna = ['status' => false, 'msg' => "Usuário não logado"];
        echo json_encode($retorna);
        exit();
    }

    if ($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro na conexão com o banco de dados: " . $conn->connect_error];
        echo json_encode($retorna);
        exit();
    }

    $id = $_SESSION["id"];
    $id_produto = $_POST["id_produto"];
    $valor = $_POST["valor"];

    if (!isset($_SESSION["id"]) || !isset($_POST["id_produto"]) || !isset($_POST["valor"])) {
        $retorna = ['status' => false, 'msg' => "Dados incompletos."];
        echo json_encode($retorna);
        exit();
    }

    $query = "SELECT quantidade FROM produto WHERE codproduto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->fetch_assoc()["quantidade"] > 0) {
        $query = "SELECT * FROM carrinho WHERE codproduto_fk = ? AND codcliente_fk = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id_produto, $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0 && $result->fetch_assoc()["quantidade"] > 0) {
            $query = "UPDATE produto SET quantidade = quantidade - 1 WHERE codproduto = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_produto);
            if(!$stmt->execute()) {
                $retorna = ['status' => false, 'msg' => "Erro ao atualizar a quantidade do produto no carrinho"];
                echo json_encode($retorna);
                exit();
            } 

            $query = "UPDATE carrinho SET quantidade = quantidade + 1 WHERE codproduto_fk = ? AND codcliente_fk = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $id_produto, $id);
            if ($stmt->execute()) {
                $retorna = ['status' => true, 'msg' => "Produto adicionado ao carrinho"];
            } else {
                $retorna = ['status' => false, 'msg' => "Erro ao atualizar a quantidade do produto no carrinho"];
            }
        } else {
            $query = "INSERT INTO carrinho(codcliente_fk, codproduto_fk, quantidade, valor_unit) VALUES (?, ?, 1, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $id, $id_produto, $valor);
            if ($stmt->execute()) {
                $retorna = ['status' => true, 'msg' => "Produto adicionado ao carrinho"];
            } else {
                $retorna = ['status' => false, 'msg' => "Erro ao adicionar produto ao carrinho"];
            }
        } 
    } else {
        $retorna = ['status' => false, 'msg' => "Produto esgotado!."];
    }
    
echo json_encode($retorna);
exit();