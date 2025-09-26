const calendarEl = document.getElementById('calendar');
const monthYearEl = document.getElementById('monthYear');
const modalEl = document.getElementById('eventModal');
let currentDate = new Date();

function renderCalendar(date = new Date()) {
    calendarEl.innerHTML = '';

    const year = date.getFullYear();
    const month = date.getMonth();
    const today = new Date();

    const totalDays = new Date(year, month + 1, 0).getDate();
    const firstDayOfMonth = new Date(year, month, 1).getDay();

    //Display mês e ano
    monthYearEl.textContent = date.toLocaleString('en-US',{
        month: 'long',
        year: 'numeric' 

    });

    const weekDays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    weekDays.forEach(day => {
        const dayEl = document.createElement('div');
        dayEl.classList.add('day-name');
        dayEl.textContent = day;
        calendarEl.appendChild(dayEl)}
    );

    for (let i = 0; i < firstDayOfMonth; i++) {
        calendarEl.appendChild(document.createElement('div'));
        
    }

    //Interar pelos dias
    for (let day = 1; day <= totalDays; day++ ){
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        const cell = document.createElement('div');
        cell.className = 'day';

        if (
            day === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
        ){
            cell.classList.add('today');
        }

        const dataEl = document.createElement('div');

        dataEl.className = 'date-number';
        dataEl.textContent = day;
        cell.appendChild(dataEl);

        const eventToday = events.filter(e => e.date === dateStr);
        const eventBox = document.createElement('div');
        eventBox.className = 'events';

        //Adicionar eventos
        eventsToday.forEach(e => {
            const ev = document.createElement('div');
            ev.className = 'event';
            
            const tituloEl = document.createElement('div');
            tituloEl.className = 'titulo';
            tituloEl.textContent = e.title;

            const localizacaoEl = document.createElement('div');
            localizacaoEl.className = 'localizacao';
            localizacaoEl.textContent = "🚩 " + e.localizacao;
            
            const timeEl = document.createElement('div');
            timeEl.className = 'time';
            timeEl.textContent = "⏰ " + e.hora_inicio + " - " + e.hora_fim;

            ev.appendChild(tituloEl);
            ev.appendChild(localizacaoEl);
            ev.appendChild(timeEl);

            eventBox.appendChild(ev);
        });

        //Overlay buttons
        const overlay = document.createElement('div');
        overlay.className = 'day-overlay';

        const addBtn = document.createElement('button');
        addBtn.className = 'overlay-btn'; 
        addBtn.textContent = '+ Add';

        addBtn.onclick = e => {
            e.stopPropagation();
            openModalForAdd(dateStr);
        };

        overlay.appendChild(addBtn);

        if(eventToday.length > 0){
            const editBtn = document.createElement('button');
            editBtn.className = 'overlay-btn';
            editBtn.textContent = 'Edit';

            editBtn.onclick = e => {
                e.stopPropagation();
                openModalForEdit(eventsToday);
            };

            overlay.appendChild(editBtn);
        }

        cell.appendChild(overlay);
        cell.appendChild(eventBox);
        calendarEl.appendChild(cell);
    }
}