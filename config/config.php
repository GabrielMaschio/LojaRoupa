<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database = "loja_roupa";

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
} 