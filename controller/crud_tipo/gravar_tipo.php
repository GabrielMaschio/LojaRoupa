<?php

include '../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro na conexão com o banco de dados: " . $conn->connect_error];
    }

    $nome_tipo = $_POST['nome_tipo'];

    $sql = "INSERT INTO tipo(nometipo) VALUES (?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $nome_tipo);

    if ($stmt->execute()) {
        $retorna = ['status' => true, 'msg' => "Tipo cadastrado com sucesso!"];
    } else {
        $retorna = ['status' => false, 'msg' => "Erro: Não foi possivel realizar o cadastrado!"];
    }

    echo json_encode($retorna);

    $stmt->close();
    $conn->close();
} else {
    $retorna = ['status' => false, 'msg' => "Não foi possivel realizar o cadastrado!"];
    echo json_encode($retorna);
    exit();
}