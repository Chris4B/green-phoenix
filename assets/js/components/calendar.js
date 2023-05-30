
import $ from 'jquery';
import 'bootstrap';
import 'moment';
import moment from "moment/moment";

// Fonction pour obtenir les plages horaires disponibles
function getAvailableTimeSlots(events, start, end, slotDuration) {
    let availableSlots = [];
    let currentSlot = start.clone();

    while (currentSlot.isBefore(end)) {
        let slotEnd = currentSlot.clone().add(slotDuration);

        // Vérifiez si le slot courant ne chevauche aucun événement
        let isSlotAvailable = events.every(function(event) {
            return currentSlot.isSameOrAfter(event.end) || slotEnd.isSameOrBefore(event.start);
        });

        // Si le slot est disponible, ajoutez-le à la liste
        if (isSlotAvailable) {
            availableSlots.push({
                start: currentSlot.clone(),
                end: slotEnd.clone()
            });
        }

        currentSlot.add(slotDuration);
    }

    return availableSlots;
}



// render initial calendar
window.onload =() => {



    let calendarElt = document.querySelector('#calendar')
    const role = calendarElt.getAttribute('data-role');
    const doctorView = calendarElt.getAttribute('data-doctor-view');
    console.log(role)
    console.log(doctorView)

    let calendar = new FullCalendar.Calendar(calendarElt, {
        initialView: 'timeGridWeek',
        locale:'fr',
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
        ],

        select: function(start){
            if(!doctorView){
                return;
            }

            //showing up the modal
            $('#appointmentModal').modal('show')

            // sending data to server
            $('#validate-btn').off('click').click(function(){
                let title = $('#patient').val();

                const data = {
                    title: title,
                    datestr: start.startStr
                }

                fetch('http://green-phoenix.test/api/events/new', {
                    method: 'POST',
                    headers:{
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)

                })
                    .then (response => response.json())
                    .then (event =>{
                        calendar.addEvent({
                            'title': event.title,
                            'start': event.datestr
                        })

                    })
            })

            //refreshing the modal

            $('#appointmentModal').on('hidden.bs.modal', function(){
                $('#patient').val('')
            })



        },
        events: function(info, successCallback, failureCallback) {
            fetch('http://green-phoenix.test/api/events')
                .then(response => response.json())
                .then(data => {
                    let events = data.map(event => ({
                        id: event.id,
                        title: event.title,
                        start: event.datestr
                    }));
                    successCallback(events);
                })
                .catch(error => {
                    console.error('Error fetching events', error);
                    failureCallback(error);
                });

        },
        eventDrop: function(info){
            if(!doctorView){
                return;
            }
            let eventData = {
                id:info.event.id,
                datestr: info.event.startStr,
                title:info.event.title
            }
            console.log(eventData)
            fetch('http://green-phoenix.test/api/events/update/'+info.event.id,{
                method:'POST',
                headers:{
                    'Content-Type':'application/json',
                },
                body: JSON.stringify(eventData)
            })


        },
        eventClick: function(info){
            if(!doctorView){
                return;
            }

            let eventTitle = info.event.title

            $('#info-patient').text(eventTitle)

            //showing up the modal
            $('#editModal').modal('show')

            //sending request to server side
            $('#confirm-btn').click(function() {

                fetch('http://green-phoenix.test/api/events/' + info.event.id, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                    .then(response => response.json())
                //deleting on client-side
                info.event.remove();
            })

        },

        editable: function(dropInfo, draggedEvent){
            if(doctorView){
                return true
            }else {
                return false;
            }
        },
        eventAllow: function(dropInfo, draggedEvent) {
            if (doctorView) {
                return true; // Les événements sont déplaçables pour le rôle "doctorView"
            } else {
                return false; // Les événements ne sont pas déplaçables pour les autres rôles
            }
        },
        // eventRender: function(info) {
        //     if (!doctorView) {
        //         info.el.classList.add('non-editable-event');
        //     }
        // },

        // get all the events of full calendar
        // eventDidMount: function(info) {
        //     let events = calendar.getEvents();
        //     console.log(events);
        //     let start = moment(info.event.start);
        //     console.log(start);
        //     let end = moment(info.event.end);
        //     let slotDuration = moment.duration(calendar.getOption('slotDuration'));
        //     console.log(slotDuration);
        //     let availableSlots = getAvailableTimeSlots(events, start, slotDuration);
        //     console.log(availableSlots);
        //     console.log('Plages horaires disponibles :');
        //     availableSlots.forEach(function(slot) {
        //         console.log(slot.start.format('HH:mm'), '-', slot.end.format('HH:mm'));
        //     });
        // },

        allDaySlot: false,
        navLinks:true,
        selectable: true, // make able to select a slot

    })
    calendar.render()

    // get all the events of full calendar

    let events =  calendar.getEvents();
    //
    console.log(events);



}