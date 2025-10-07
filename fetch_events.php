<?php
// Inclui a conexão com o banco de dados
include 'connection.php';

// Array que armazenará os eventos formatados
$events = [];

// 📅 Busca todos os compromissos
// Ordena por data e hora de início
$result = $conn->query("SELECT * FROM meu_calendario ORDER BY data_inicio, hora_inicio");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $startDate = new DateTime($row['data_inicio']);
        $endDate = new DateTime($row['data_fim']);

        // Cria uma cópia do evento para CADA dia entre a data de início e fim
        // Isso permite que eventos multi-dias apareçam em cada célula
        while ($startDate <= $endDate) {
            $events[] = [
                'id'            => $row['id'],
                'titulo'        => $row['titulo'],
                'localizacao'   => $row['localizacao'],
                'descricao'     => $row['descricao'],
                
                // CRÍTICO: Formatar a data correta para o dia atual do loop
                'data_inicio'   => $startDate->format('Y-m-d'),
                
                'data_fim'      => $row['data_fim'],
                'hora_inicio'   => $row['hora_inicio'],
                'hora_fim'      => $row['hora_fim']
            ];
            // Move para o próximo dia no loop
            $startDate->modify('+1 day'); 
        }
    }
}

$conn->close();

// 🚨 CRÍTICO: Configurar o cabeçalho e imprimir o JSON
header('Content-Type: application/json');
echo json_encode($events);

// Encerra o script
exit();