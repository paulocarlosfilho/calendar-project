<?php

// Conexão com o banco de dados
include 'connection.php';

$sucessMsg = "";
$errorMsg = "";

# Tratar a Adição do Compromisso
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "add") {

    $titulo = trim($_POST['titulo'] ?? "");
    $localizacao = trim($_POST['localizacao'] ?? "");
    $data_inicio = $_POST["data_inicio"] ?? "";
    $data_fim = $_POST["data_fim"] ?? "";
    $hora_inicio = $_POST["hora_inicio"] ?? null;
    $hora_fim = $_POST["hora_fim"] ?? null;

    if ($titulo && $localizacao && $data_inicio && $data_fim) {
        $stmt = $conn->prepare("INSERT INTO meu_calendario (titulo, localizacao, data_inicio, data_fim, hora_inicio, hora_fim) VALUES (?, ?, ?, ?, ?, ?)");
    };    

    $stmt->bind_param("ssss", $titulo, $localizacao, $data_inicio, $data_fim);
    $stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
    exit();
}else{
    header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
    exit();
}


# Handler Edição do Compromisso
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "edit") {

    $id = $_POST["id"] ?? null;
    $titulo = trim($_POST['titulo'] ?? "");
    $localizacao = trim($_POST['localizacao'] ?? "");
    $data_inicio = $_POST["data_inicio"] ?? "";
    $data_fim = $_POST["data_fim"] ?? "";
    
    if ($id && $titulo && $localizacao && $data_inicio && $data_fim) {
        $stmt = $conn->prepare("UPDATE meu_calendario SET titulo = ?, localizacao = ?, data_inicio = ?, data_fim = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $titulo, $localizacao, $data_inicio, $data_fim, $id);
        $stmt->execute();     
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=2");
        exit();
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=2");
        exit();
    }
}