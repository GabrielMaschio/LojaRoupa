<?php

include '../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro: Erro na conexão com o banco de dados!"];
    }

    $id = $_POST['id'];
    $nome_tipo = $_POST['nome_tipo'];

    if(empty($id)) {
        $retorna = ['status' => false, 'msg' => "Erro: Não foi possivel acessar o elemento!"];
    } else if($nome_tipo < 4) {
        $retorna = ['status' => false, 'msg' => "Erro: O nome do tipo deve ter mais que dois caracteres!"];
    } else {
       
     
        $stmt = $conn->prepare("UPDATE tipo SET nometipo = ? WHERE codtipo = ?");
        $stmt->bind_param("si", $nome_tipo, $id);

        
        // Executa a query
        if ($stmt->execute()) {
            $retorna = ['status' => true, 'msg' => "Tipo atualizado com sucesso!"];
        } else {
            $retorna = ['status' => false, 'msg' => "Erro: Tipo não atualizado!"];
        }
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