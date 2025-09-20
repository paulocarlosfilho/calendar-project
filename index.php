<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadelário - pcsf</title>

    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <header>
        <h1>📆 Calendário PCFS<br>Meu próprio Calendário</h1>
</header>

<!-- Clock -->
<div class="clock-container">
    <div id="clock"></div>
</div>

<!-- Caledar Section -->
<div class="calendar">
    <div class="nav-btn-container">
        <button class="nav-btn">⏪</button>
        <h2 id="monthYear" style="margin:0"></h2>
        <button class="nav-btn">⏩</button>
    </div>

    <div class="calendar-grid" id="calendar"></div>
</div>

<!-- Modal for Add/Edit/Delete Appointment -->
<div id="eventSelectorWrapper">
        <strong>Select Event</strong>
    </label>
    <select id="eventSelector">
        <option disable selector>Choose Event...</option>
    </select>
</div>

<!-- <Main Form-->
<form method="POST" id="eventForm">
    <input type="hidden" name="action" id="formAction" value="add">
    <input type="hidden" name="event_id" id="eventId">
    <br>

    <label for="courseName">Course Title:</label>
    <input type="text" id="course_name" name="courseName" required>
    <br>
    
    <label for="instructorName">Instructor Name:</label>
    <input type="text" name="instructor_name" id="instructorName" require>
    <br>

    <label for="startDate">Start Date:</label>
    <input type="date" name="start_date" id="startDate" required>
    <br>

    <label for="endDate">End Date:</label>
    <input type="date" name="end_date" id="endDate" required>
    <button type="submit">💾 Save</button>
    <br>

</form>

<!-- Delete Form -->

<form method="POST" onsubmit="return confirm('Are you sure you want to delete this event? (Tem certeza que deseja excluir o item?)')">
    <input type="hidden" name="action" id="formAction" value="delete">
    <input type="hidden" name="event_id" id="formAction" value="deleteEventId">
    <button type="submit" class="sobmit-btn">🗑️ Delete</button>
</form>

<!-- ❌ Cancelado -->

</body>

</html>