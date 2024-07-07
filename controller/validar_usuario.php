<?php
session_start();

if (empty($_POST) || empty($_POST["usuario"]) || empty($_POST["senha"])) {
    $retorna = ['status' => false, 'msg' => "Os campos não foram preenchidos corretamente: " . $conn->error];
    echo json_encode($retorna);
    exit();
}

include '../config/config.php';

$usuario = trim($_POST["usuario"]);
$senha = trim($_POST["senha"]);

$sql = "SELECT * FROM usuario WHERE usuario = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    $retorna = ['status' => false, 'msg' => "Falha ao preparar a consulta: " . $conn->error];
    echo json_encode($retorna);
    exit();
}

$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($senha, $row['senha'])) {
        // Consulta adicional para obter o caminho da imagem do perfil
        $sql_img = "SELECT pp FROM cliente WHERE codcliente = ?";
        $stmt_img = $conn->prepare($sql_img);

        if ($stmt_img === false) {
            $retorna = ['status' => false, 'msg' => "Falha ao preparar a consulta da imagem: " . $conn->error];
            echo json_encode($retorna);
            exit();
        }

        $stmt_img->bind_param("s", $row['codcliente_fk']);
        $stmt_img->execute();
        $result_img = $stmt_img->get_result();

        if ($result_img->num_rows === 1) {
            $row_img = $result_img->fetch_assoc();
            $img_path = $row_img['pp'];
        } else {
            $img_path = null; // ou um valor padrão
        }

        $_SESSION["loggedin"] = true;
        $_SESSION["usuario"] = $usuario;
        $_SESSION["id"] = $row['codcliente_fk'];
        $_SESSION["img_path"] = $img_path;

        $retorna = ['status' => true, 'msg' => "Logado!", 'img_path' => $img_path];
    } else {
        $retorna = ['status' => false, 'msg' => "Senha incorreta."];
    }
} else {
    $retorna = ['status' => false, 'msg' => "Usuário incorreto."];
}

echo json_encode($retorna);

$stmt->close();
$conn->close();
?>
