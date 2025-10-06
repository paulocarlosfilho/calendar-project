<?php

// 1. Connection to Local MySQL Server (using XAMPP or MAMPP)

$host = "db"; //"localhost"; 
$username = "root"; 
$password = "password_root";
$dbname = "meu_calendario";// "meu_calendario";

// Conexão com 4 parâmetros: (servidor, usuário, senha, nome_do_banco)
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>