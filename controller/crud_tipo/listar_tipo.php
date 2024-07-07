<?php
    include '../../model/tipo.php';
    include '../../Config/Config.php';

    if ($conn->connect_error) {
        die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
    }
    
    $query = "SELECT * FROM tipo ORDER BY nometipo ASC";
    $result = $conn->query($query);
    
    $tipos = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tipo = new Tipo($row["codtipo"], $row["nometipo"]);

            $tipos[] = $tipo;
        }
    } 

    echo json_encode($tipos);

    $conn->close();