<?php

// Conexão com o banco de dados
include 'connection.php';

$sucessMsg = "";
$errorMsg = "";
$eventsFromDb = []; // Array para armazenar eventos do banco de dados

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

# 🗑 Handler Exclusão do Compromisso
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? '') === "delete") {

    $id = $_POST["id"] ?? null;

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM meu_calendario WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=3");
        exit();
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=3");
        exit();
    }
}


# Sucesso e Erro Mensagens 
if(isset($_GET['success'])) {
    $sucessMsg = match ($_GET['success']) {
         '1' => "✅ Compromisso adicionado com sucesso!",
         '2' => "✅ Compromisso atualizado com sucesso!",
         '1' => "🗑 Compromisso deletado com sucesso!",
         default => ''
    };
}

if(isset($_GET['error'])) {
    $errorMsg = match ($_GET['error']) {
         '1' => "❌ Erro ao adicionar compromisso. Por favor, tente novamente.",
         '2' => "❌ Erro ao atualizar compromisso. Por favor, tente novamente.",
         '3' => "❌ Erro ao deletar compromisso. Por favor, tente novamente.",
         default => ''
    };
}

# 📅 Buscar Todos os Compromissos e Distribuir (ou Exibir) em um Intervalo de Datas.
$result = $conn->query("SELECT * FROM meu_calendario ORDER BY data_inicio, hora_inicio");
if($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $start = new DateTime($row['data_inicio']);
        $end = new DateTime($row['data_fim']);

        while($start <= $end) {
            $eventsFromDb[] = [
                'id' => $row['id'],
                'titulo' => "{$row['titulo']} - {$row['localizacao']}",
                'data' => $start->format('Y-m-d'),
                'start' => $row['hora_inicio'],
                'end' => $row['hora_fim']
            ];

            $start->modify('+1 day');
        }
    }
} 

$conn->close();

?>