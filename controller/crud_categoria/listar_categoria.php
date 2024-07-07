<?php
    include '../../model/categoria.php';
    include '../../Config/Config.php';

    if ($conn->connect_error) {
        $retorna = ['status' => false, 'msg' => "Erro na conexão com o banco de dados: " . $conn->connect_error];
        echo json_encode($retorna);
        exit();
    }

    $query = "SELECT * FROM categoria ORDER BY nome_categoria ASC";
    $result = $conn->query($query);
    
    $categorias = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Categoria = new Categoria($row["codcategoria"], $row["nome_categoria"]);
            $categorias[] = $Categoria;
        }
    } 

    echo json_encode($categorias);

    $conn->close();