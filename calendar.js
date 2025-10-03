const calendarEl = document.getElementById('calendar');
const monthYearEl = document.getElementById('monthYear');
const modalEl = document.getElementById('eventModal');
let currentDate = new Date();
let events = []; // Array que armazena os eventos buscados do PHP

// Função que busca dados e renderiza o calendário
async function fetchAndRenderEvents(date) {
    const year = date.getFullYear();
    const month = date.getMonth() + 1; // Mês atual

    try {
        // Chama o novo arquivo PHP que retorna o JSON de eventos
        // Nota: Por enquanto, a query no PHP busca TUDO. Se quiser filtrar, descomente as variáveis GET:
        const response = await fetch(`fetch_events.php`); 
        
        if (!response.ok) {
            throw new Error('Erro ao carregar eventos: ' + response.statusText);
        }

        // Popula a variável global 'events'
        events = await response.json(); 
        
        // Renderiza o calendário com os eventos carregados
        renderCalendar(date); 
        
    } catch (error) {
        console.error("Falha no Fetch:", error);
        // Renderiza mesmo que falhe, mas sem eventos
        renderCalendar(date); 
    }
}


function renderCalendar(date = new Date()) {
    calendarEl.innerHTML = '';

    const year = date.getFullYear();
    const month = date.getMonth();
    const today = new Date();

    const totalDays = new Date(year, month + 1, 0).getDate();
    const firstDayOfMonth = new Date(year, month, 1).getDay();

    //Display mês e ano
    monthYearEl.textContent = date.toLocaleString('pt-BR',{ // Mudado para pt-BR
        month: 'long',
        year: 'numeric' 
    });

    const weekDays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb']; // Dias em Português
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

        // FILTRO: Usa 'data_inicio' que é a chave que o fetch_events.php está retornando para cada dia.
        const eventsToday = events.filter(e => e.data_inicio === dateStr);
        const eventBox = document.createElement('div');
        eventBox.className = 'events';

        //Adicionar eventos
        eventsToday.forEach(e => {
            const ev = document.createElement('div');
            ev.className = 'event';
            
            const tituloEl = document.createElement('div');
            tituloEl.className = 'titulo';
            tituloEl.textContent = e.titulo; 

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

        if(eventsToday.length > 0){
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

// Add event Modal
function openModalForAdd(dateStr) {
    document.getElementById('formAction').value = 'add'; 
    document.getElementById('eventId').value = "";
    document.getElementById('deleteEventId').value = "";
    document.getElementById('titulo').value = "";
    document.getElementById('localizacao').value = "";
    document.getElementById('descricao').value = ""; // Novo campo
    document.getElementById('startDate').value = dateStr;
    document.getElementById('endDate').value = dateStr;
    document.getElementById('startTime').value = "09:00";
    document.getElementById('endTime').value = "10:00";

    const selector = document.getElementById('eventSelector');
    const wrapper = document.getElementById('eventSelectorWrapper');

    if (selector && wrapper) {
        selector.innerHTML = '';
        wrapper.style.display = 'none';
    }

    modalEl.style.display = 'flex';
}

// Edit Event modal
function openModalForEdit(eventsOnDate) {
    document.getElementById('formAction').value = 'edit';
    modalEl.style.display = 'flex'; 

    const selector = document.getElementById('eventSelector');
    const wrapper = document.getElementById('eventSelectorWrapper');    
    selector.innerHTML = '<option disabled selected>Escolha o Evento...</option>'; // Texto em Português
 
    eventsOnDate.forEach(e => {
        const option = document.createElement('option');
        option.value = JSON.stringify(e);
        option.textContent = `${e.titulo} ${e.hora_inicio} ➡ ${e.hora_fim}`; 
        selector.appendChild(option);
    });

    if(eventsOnDate.length > 1){
        wrapper.style.display = 'block';
    }else{
        wrapper.style.display = 'none';
    }

    handleEventSelection(JSON.stringify(eventsOnDate[0]));
}

function handleEventSelection(eventJSON) {
    const event = JSON.parse(eventJSON);
    
    document.getElementById('eventId').value = event.id;
    document.getElementById('deleteEventId').value = event.id;
    
    document.getElementById('titulo').value = event.titulo || ''; 
    document.getElementById('localizacao').value = event.localizacao || '';
    document.getElementById('descricao').value = event.descricao || ''; 
    
    document.getElementById('startDate').value = event.data_inicio || ''; 
    document.getElementById('endDate').value = event.data_fim || '';
    document.getElementById('startTime').value = event.hora_inicio || ''; 
    document.getElementById('endTime').value = event.hora_fim || '';
}

// Close modal
function closeModal() {
    modalEl.style.display = 'none';
    // Após fechar o modal, recarrega os eventos (útil após adicionar/editar/deletar)
    // fetchAndRenderEvents(currentDate); 
}

// Month navigation
function changeMonth(offset) {
    currentDate.setMonth(currentDate.getMonth() + offset);
    // Chamada corrigida:
    fetchAndRenderEvents(currentDate);
}

// Live digital clock
function updateClock() {
    const now = new Date();
    const clock = document.getElementById('clock');
    clock.textContent = [
        now.getHours().toString().padStart(2, '0'),
        now.getMinutes().toString().padStart(2, '0'),
        now.getSeconds().toString().padStart(2, '0')
    ].join(':');
}

// ----------------------------------------
// INICIALIZAÇÃO
// ----------------------------------------

// CHAMA O FETCH E RENDERIZA PELA PRIMEIRA VEZ
fetchAndRenderEvents(currentDate); 
updateClock(); 
setInterval(updateClock, 1000);