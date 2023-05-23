// render initial calendar


window.onload =() => {


    let calendarElt = document.querySelector('#calendar')



    let calendar = new FullCalendar.Calendar(calendarElt, {
        initialView: 'timeGridWeek',
        locale: 'fr',
        timeZone: 'Europe/Paris',
        slotDuration: '00:45:00',
        slotMinTime: '08:00',
        slotMaxTime: '18:00',
        themeSystem: 'bootstrap5',
        headerToolbar: {
            start: 'prevYear,prev,next,nextYear today',
            center: 'title',
            end: 'dayGridMonth,timeGridDay'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week: 'Semaine',
            list: 'liste',
            day: 'Jour'
        },
        views: {
            week: {
                hiddenDays: [0]
            }
        },
        businessHours: [
            {
                daysOfWeek: [1, 2, 3, 4, 5, 6],
                startTime: '08:00',
                endTime: '12:00',
            },
            {
                daysOfWeek: [1, 2, 3, 4, 5, 6],
                startTime: '14:00',
                endTime: '17:45'
            }
        ]
    })
    calendar.render()
}
