<?php
    include '../Config/Config.php';
    session_start();

    if($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro: Não foi possível conectar ao banco de dados. " . $conn->connect_error];
        echo json_encode($retorna);
        exit;
    }

    $id = $_SESSION['id'];

    $query = "CALL InserirVenda(?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        $retorna = ['status' => false, 'msg' => "Erro: Não foi possível preparar a consulta. " . $conn->error];
        echo json_encode($retorna);
        exit;
    }
    $stmt->bind_param("i", $id);
    if($stmt->execute()) {
        $retorna = ['status' => true, 'msg' => "Compra finalizada com sucesso!"];
    } else {
        $retorna = ['status' => false, 'msg' => "Erro: Não foi possível executar a consulta. " . $stmt->error];
    }

    echo json_encode($retorna);
    $stmt->close();
    $conn->close();
