<?php

include '../../config/config.php';

header('Content-Type: application/json');

function retornaErro($msg) {
    $retorna = ['status' => false, 'msg' => $msg];
    echo json_encode($retorna);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Verifica se a conexão com o banco de dados foi estabelecida
        if ($conn->connect_error) {
            retornaErro("Erro: Erro na conexão com o banco de dados!");
        }

        // Verifica se os dados do formulário foram recebidos corretamente
        $id = $_POST['id'] ?? null;
        $nome_produto = $_POST['nome_produto'] ?? '';
        $quantidade = $_POST['quantidade'] ?? 0;
        $valor = $_POST['valor'] ?? 0;
        $desconto = $_POST['desconto'] ?? 0;
        $id_categoria = $_POST['id_categoria'] ?? null;
        $id_tipo = $_POST['id_tipo'] ?? null;
        $imagem = $_FILES['pp'] ?? null;

        if (empty($id) || strlen($nome_produto) < 4) {
            retornaErro("Erro: Dados do formulário inválidos!");
        }

        // Diretório de upload
        $directory = '../../upload/produto/';

        // Verifica se uma nova imagem foi enviada
        if ($imagem && isset($imagem['name']) && !empty($imagem['name'])) {
            $img_name = $imagem['name'];
            $tmp_name = $imagem['tmp_name'];
            $error = $imagem['error'];

            if ($error === 0) {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_to_lc = strtolower($img_ex);

                $allowed_exs = ['jpg', 'jpeg', 'png'];

                if (in_array($img_ex_to_lc, $allowed_exs)) {
                    // Nome único para a imagem
                    $new_img_name = uniqid($nome_produto, true) . '.' . $img_ex_to_lc;
                    $img_upload_path = $directory . $new_img_name;

                    // Verifica se a imagem já existe no servidor
                    if (!file_exists($img_upload_path)) {
                        // Move o arquivo para o diretório de upload
                        if (move_uploaded_file($tmp_name, $img_upload_path)) {
                            // Atualiza o registro no banco de dados
                            $sql = "UPDATE produto SET nomeproduto = ?, quantidade = ?, valor = ?, desconto = ?, codcategoria_fk = ?, codtipo_fk = ?, pp = ? WHERE codproduto = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("sidiiisi", $nome_produto, $quantidade, $valor, $desconto, $id_categoria, $id_tipo, $new_img_name, $id);

                            if ($stmt->execute()) {
                                $retorna = ['status' => true, 'msg' => "Produto atualizado com sucesso!"];
                            } else {
                                retornaErro("Erro: Produto não atualizado com sucesso!");
                            }

                            $stmt->close();
                        } else {
                            retornaErro("Erro ao mover o arquivo carregado!");
                        }
                    } else {
                        retornaErro("Erro: A imagem já existe no servidor!");
                    }
                } else {
                    retornaErro("Erro: Você não pode enviar arquivos deste tipo");
                }
            } else {
                retornaErro("Erro desconhecido durante o upload!");
            }
        } else {
            // Nenhuma nova imagem foi enviada
            $sql = "UPDATE produto SET nomeproduto = ?, quantidade = ?, valor = ?, desconto = ?, codcategoria_fk = ?, codtipo_fk = ? WHERE codproduto = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sidiiii", $nome_produto, $quantidade, $valor, $desconto, $id_categoria, $id_tipo, $id);

            if ($stmt->execute()) {
                $retorna = ['status' => true, 'msg' => "Produto atualizado com sucesso!"];
            } else {
                retornaErro("Erro: Produto não atualizado com sucesso!");
            }

            $stmt->close();
        }

        // Fecha a conexão com o banco de dados
        $conn->close();
        echo json_encode($retorna);
    } catch (Exception $e) {
        retornaErro("Erro: " . $e->getMessage());
    }
} else {
    retornaErro("Erro: O formulário não foi enviado!");
}