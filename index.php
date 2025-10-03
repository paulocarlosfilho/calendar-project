<?php
    // Incluir o handler de mensagens
    include 'calendar.php'; 
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário - pcsf</title>

    <link rel="stylesheet" href="stylesheet.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    
</head>

<body>

    <header>
        <h1>📆 Calendário PCFS<br>Meu próprio Calendário</h1>
    </header>

    <?php if($sucessMsg): ?>
        <div class="alert success"><?php echo $sucessMsg; ?></div>
    <?php endif; ?>

    <?php if($errorMsg): ?>
        <div class="alert erro"><?php echo $errorMsg; ?></div>
    <?php endif; ?>


    <div class="clock-container">
        <div id="clock"></div>
    </div>

    <div class="calendar">
        <div class="nav-btn-container">
            <button class="nav-btn" onclick="changeMonth(-1)">⏪</button>
            <h2 id="monthYear" style="margin:0"></h2>
            <button class="nav-btn" onclick="changeMonth(1)">⏩</button>
        </div>

        <div class="calendar-grid" id="calendar"></div>
    </div>

    <div class="modal" id="eventModal">
        <div class="modal-content">

            <div id="eventSelectorWrapper">
                <strong>Selecione o Evento:</strong>
                <select id="eventSelector"></select>
            </div>

        <form method="POST" action="calendar.php" id="eventForm"> 
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="eventId" id="eventId"> 
            <br>

            <label for="titulo">Título do Evento:</label>
            <input type="text" id="titulo" name="titulo" required>
            <br>
            
            <label for="localizacao">Localização:</label>
            <input type="text" name="localizacao" id="localizacao" required>

            <label for="descricao">Descrição (Opcional):</label>
            <textarea id="descricao" name="descricao"></textarea>
            <br>

            <label for="startDate">Data Início:</label>
            <input type="date" name="startDate" id="startDate" required>

            <label for="endDate">Data Fim:</label>
            <input type="date" name="endDate" id="endDate" required>
            
            <label for="startTime">Hora Início:</label>
            <input type="time" name="startTime" id="startTime" required>

            <label for="endTime">Hora Fim:</label>
            <input type="time" name="endTime" id="endTime" required>
            
            <button type="submit">💾 Salvar</button>
        </form>

        <form method="POST" action="calendar.php" onsubmit="return confirm('Tem certeza que deseja excluir o item?')">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="deleteEventId" id="deleteEventId"> 
            <button type="submit" class="sobmit-btn">🗑️ Deletar</button>
        </form>

        <button type="button" onclick="closeModal()">❌ Cancelar</button>

        </div>
    </div>

    <script>
        const events = <?php echo json_encode($eventsFromDb); ?>;
    </script>

    <script src="calendar.js"></script>
    <script>
        // Adiciona um listener para atualizar o formulário quando o evento é selecionado no dropdown
        document.getElementById('eventSelector').onchange = function() {
            handleEventSelection(this.value);
        };
    </script>
</body>
</html>