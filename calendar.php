<?php

// Conexão com o banco de dados
include 'connection.php'; 

// Inicializa as variáveis de mensagem
$sucessMsg = "";
$errorMsg = "";

// -------------------------------------------------------------
// 1. Handler de Adição (POST action=add)
// -------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "add") {

    // Sanitiza e coleta os dados do formulário
    $titulo = trim($_POST['titulo'] ?? "");
    $localizacao = trim($_POST['localizacao'] ?? "");
    $descricao = trim($_POST['descricao'] ?? "");
    $data_inicio = $_POST["startDate"] ?? ""; 
    $data_fim = $_POST["endDate"] ?? "";
    $hora_inicio = $_POST["startTime"] ?? ''; 
    $hora_fim = $_POST["endTime"] ?? '';

    // Validação básica dos campos (descricao é opcional)
    if ($titulo && $localizacao && $data_inicio && $data_fim) {
        
        $stmt = $conn->prepare("INSERT INTO meu_calendario (titulo, localizacao, descricao, data_inicio, data_fim, hora_inicio, hora_fim) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssssss", $titulo, $localizacao, $descricao, $data_inicio, $data_fim, $hora_inicio, $hora_fim);
        
        if ($stmt->execute()) {
            header("Location: index.php?success=1");
        } else {
            // Falha na execução do DB
            header("Location: index.php?error=1");
        }
        $stmt->close();
    } else {
        header("Location: index.php?error=1");
    }
    exit();
}


// -------------------------------------------------------------
// 2. Handler Edição (POST action=edit)
// -------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "edit") {

    $id = $_POST["eventId"] ?? null; 
    $titulo = trim($_POST['titulo'] ?? "");
    $localizacao = trim($_POST['localizacao'] ?? "");
    $descricao = trim($_POST['descricao'] ?? "");
    $data_inicio = $_POST["startDate"] ?? "";
    $data_fim = $_POST["endDate"] ?? "";
    $hora_inicio = $_POST["startTime"] ?? '';
    $hora_fim = $_POST["endTime"] ?? '';
    
    // Validação básica
    if ($id && $titulo && $localizacao && $data_inicio && $data_fim) {
        
        $stmt = $conn->prepare("UPDATE meu_calendario SET titulo = ?, localizacao = ?, descricao = ?, data_inicio = ?, data_fim = ?, hora_inicio = ?, hora_fim = ? WHERE id = ?");
        
        $stmt->bind_param("sssssssi", $titulo, $localizacao, $descricao, $data_inicio, $data_fim, $hora_inicio, $hora_fim, $id);
        
        if ($stmt->execute()) {
            header("Location: index.php?success=2");
        } else {
            header("Location: index.php?error=2");
        }
        $stmt->close();
    } else {
        header("Location: index.php??error=2");
    }
    exit();
}

// -------------------------------------------------------------
// 3. Handler Exclusão (POST action=delete)
// -------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "delete") {

    $id = $_POST["deleteEventId"] ?? null; 

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM meu_calendario WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            header("Location: index.php?success=3");
        } else {
            header("Location: index.php?error=3");
        }
        $stmt->close();
    } else {
        header("Location: index.php?error=3");
    }
    exit();
}


// -------------------------------------------------------------
// 4. Mensagens de Sucesso e Erro
// -------------------------------------------------------------
if(isset($_GET['success'])) {
    $sucessMsg = match ($_GET['success']) {
        '1' => "✅ Compromisso adicionado com sucesso!",
        '2' => "✅ Compromisso atualizado com sucesso!",
        '3' => "🗑 Compromisso deletado com sucesso!", 
        default => ''
    };
}

if(isset($_GET['error'])) {
    $errorMsg = match ($_GET['error']) {
        '1' => "❌ Erro ao adicionar compromisso. Verifique os campos.",
        '2' => "❌ Erro ao atualizar compromisso. Verifique os campos.",
        '3' => "❌ Erro ao deletar compromisso. ID não encontrado.",
        default => ''
    };
}

// Fechamento da conexão
$conn->close();

?>