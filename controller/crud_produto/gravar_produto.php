<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro na conexão com o banco de dados: " . $conn->connect_error];
        echo json_encode($retorna);
        exit();
    }

    $nome_produto = $_POST['nome_produto'] ?? null;
    $quantidade = $_POST['quantidade'] ?? null;
    $valor = $_POST['valor'] ?? null;
    $desconto = $_POST['desconto'] ?? null;
    $id_categoria = $_POST['id_tipo'] ?? null;
    $id_tipo = $_POST['id_tipo'] ?? null;
    $imagem = $_FILES['pp'] ?? null;

    if ($imagem && isset($imagem['name']) && !empty($imagem['name'])) {
        $img_name = $imagem['name'];
        $tmp_name = $imagem['tmp_name'];
        $error = $imagem['error'];
        
        if ($error === 0) {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_to_lc = strtolower($img_ex);

            $allowed_exs = ['jpg', 'jpeg', 'png'];
           
            if (in_array($img_ex_to_lc, $allowed_exs)) {
                $new_img_name = uniqid($nome_produto, true) . '.' . $img_ex_to_lc;
                $img_upload_path = '../../upload/produto/' . $new_img_name;
                
                if (move_uploaded_file($tmp_name, $img_upload_path)) {
                    $sql = "INSERT INTO produto(nomeproduto, quantidade, valor, desconto, codtipo_fk, codcategoria_fk, pp) VALUES (?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);

                    $stmt->bind_param("sssssss", $nome_produto, $quantidade, $valor, $desconto, $id_tipo, $id_categoria, $new_img_name);

                    if ($stmt->execute()) {
                        $retorna = ['status' => true, 'msg' => "Produto cadastrado com sucesso!"];
                    } else {
                        $retorna = ['status' => false, 'msg' => "Erro: Não foi possível realizar o cadastro!"];
                    }

                    $stmt->close();
                } else {
                    $retorna = ['status' => false, 'msg' => "Erro ao mover o arquivo carregado!"];
                }
            } else {
                $retorna = ['status' => false, 'msg' => "Erro: Você não pode enviar arquivos deste tipo"];
            }
        } else {
            $retorna = ['status' => false, 'msg' => "Erro desconhecido durante o upload!"];
        }
    } else {
        $sql = "INSERT INTO produto(nomeproduto, quantidade, valor, desconto, codtipo_fk, codcategoria_fk) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssssss", $nome_produto, $quantidade, $valor, $desconto, $id_tipo, $id_categoria);

       if($stmt->execute()) {
            $retorna = ['status' => true, 'msg' => "Produto cadastrado com sucesso!"];
        } else {
            $retorna = ['status' => false, 'msg' => "Erro: Não foi possível realizar o cadastro!" . $stmt->error];
        }

        $stmt->close();
    }

    $conn->close();
    echo json_encode($retorna);
} else {
    $retorna = ['status' => false, 'msg' => "Erro: Requisição inválida!"];
    echo json_encode($retorna);
    exit();
}