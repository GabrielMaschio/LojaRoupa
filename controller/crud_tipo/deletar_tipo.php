<?php

// Incluir arquivo de configuração
include '../../config/config.php';

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se ocorreu algum erro na conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $id = $_POST['id'];

    $sql = "DELETE FROM tipo WHERE codtipo = ?";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $id);

    // Executa a query
    if ($stmt->execute()) {
        $retorna = ['status' => true, 'msg' => "Tipo deletada com sucesso!"];
    } else {
        $retorna = ['status' => false, 'msg' => "Erro: Tipo não deletada!"];
    }

    echo json_encode($retorna);

    // Fecha a conexão
    $stmt->close();
    $conn->close();
} else {
    // Se o formulário não foi enviado, redireciona para a página de cadastro
    $retorna = ['status' => false, 'msg' => "Erro: O formulário não foi enviado!"];
    echo json_encode($retorna);
    exit();
}
