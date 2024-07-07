<?php

include '../../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro: Erro na conexão com o banco de dados!"];
    }

    $id = $_POST['id'];
    $nome_categoria = $_POST['nome_categoria'];

    if(empty($id)) {
        $retorna = ['status' => false, 'msg' => "Erro: Não foi possivel acessar o elemento!"];
    } else if($nome_categoria < 4) {
        $retorna = ['status' => false, 'msg' => "Erro: O nome da categoria deve ter mais que dois caracteres!"];
    } else {
       
     
        $stmt = $conn->prepare("UPDATE categoria SET nome_categoria = ? WHERE codcategoria = ?");
        $stmt->bind_param("si", $nome_categoria, $id);

        
        // Executa a query
        if ($stmt->execute()) {
            $retorna = ['status' => true, 'msg' => "Categoria atualizada com sucesso!"];
        } else {
            $retorna = ['status' => false, 'msg' => "Erro: Categoria não atualizada!"];
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