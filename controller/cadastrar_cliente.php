<?php
session_start();

include '../config/config.php';

if ($conn->connect_error) {
    $retorna = ['status' => false, 'msg' => "Falha na conexão: " . $conn->connect_error];
}

if(isset($_POST['nome_cliente']) && isset($_POST['user']) && isset($_POST['email']) && isset($_POST['telefone']) && isset($_POST['key'])) {
    $nomecliente = $_POST['nome_cliente'];
    $usuario = $_POST['user'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['key'];
    $imagem = $_FILES['pp'];

    $hashed_password = password_hash($senha, PASSWORD_DEFAULT);

    if (isset($_FILES['pp']['name']) && !empty($_FILES['pp']['name'])) {
        
        $img_name = $_FILES['pp']['name'];
        $tmp_name = $_FILES['pp']['tmp_name'];
        $error = $_FILES['pp']['error'];
        
        if($error === 0){
           $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
           $img_ex_to_lc = strtolower($img_ex);

           $allowed_exs = array('jpg', 'jpeg', 'png');
           
           if(in_array($img_ex_to_lc, $allowed_exs)){
              
              $new_img_name = uniqid($usuario, true).'.'.$img_ex_to_lc;
              
              $img_upload_path = '../upload/cliente/'.$new_img_name;
              
              move_uploaded_file($tmp_name, $img_upload_path);
              
                $stmt = $conn->prepare("CALL cadastrar_cliente_img(?, ?, ?, ?, ?, ?, @p_status, @p_message)");
                if (!$stmt) {
                    $retorna = ['status' => false, 'msg' => "Preparação falhou: " . $conn->error];
                }

                $stmt->bind_param("ssssss", $nomecliente, $email, $telefone, $usuario, $hashed_password, $new_img_name);

                if (!$stmt->execute()) {
                    $retorna = ['status' => false, 'msg' => "Execução falhou: " . $conn->error];
                }

                $result = $conn->query("SELECT @p_status AS status, @p_message AS message");
                $row = $result->fetch_assoc();
                $status = $row['status'];
                $message = $row['message'];

                if ($status != 0) {
                    $retorna = ['status' => false, 'msg' => "Erro: " . $message];
                } else {
                    $retorna = ['status' => true, 'msg' => "Sucesso: Usuário cadastrado"];
                }

                $stmt->close();
            }else {
                $retorna = ['status' => false, 'msg' => "Erro: Você não pode enviar arquivos deste tipo"];
            }
        }else {
            $retorna = ['status' => false, 'msg' => "Erro desconhecido durante o upload!"];
        }
    }else {
        $stmt = $conn->prepare("CALL  cadastrar_cliente(?, ?, ?, ?, ?, @p_status, @p_message)");
        if (!$stmt) {
            $retorna = ['status' => false, 'msg' => "Preparação falhou: " . $conn->error];
        }

        $stmt->bind_param("sssss", $nomecliente, $email, $telefone, $usuario, $hashed_password);

        if (!$stmt->execute()) {
            $retorna = ['status' => false, 'msg' => "Execução falhou: " . $conn->error];
        }

        // Obtém os valores dos parâmetros de saída
        $result = $conn->query("SELECT @p_status AS status, @p_message AS message");
        $row = $result->fetch_assoc();
        $status = $row['status'];
        $message = $row['message'];

        if ($status != 0) {
            $retorna = ['status' => false, 'msg' => "Erro: " . $message];
        } else {
            // Se o cadastro foi concluído com sucesso, de  fine o usuário como logado
            // Implemente a lógica de login aqui
            $retorna = ['status' => true, 'msg' => "Usuário cadastrado com sucesso!"];
        }

        // Fecha a instrução
        $stmt->close();
   }
} else {
    // Captura e exibe a mensagem de erro
    $retorna = ['status' => false, 'msg' => "Erro: Ocorreu algúm erro com os campos"];
}

echo json_encode($retorna);

// Fecha a conexão
$conn->close();