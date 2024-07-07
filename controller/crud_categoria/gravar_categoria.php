<?php

include '../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro na conexão com o banco de dados: " . $conn->connect_error];
        echo json_encode($retorna);
        exit();
    }

    $nome_categoria = $_POST['nome_categoria'];

    $sql = "INSERT INTO categoria(nome_categoria) VALUES (?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $nome_categoria);

    if ($stmt->execute()) {
        $retorna = ['status' => true, 'msg' => "Categoria cadastrada com sucesso!"];
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