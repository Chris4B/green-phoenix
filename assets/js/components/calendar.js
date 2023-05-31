
import $ from 'jquery';
import 'bootstrap';
import 'moment';
import moment from "moment/moment";


let events = [];

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

                    function findAvailableTimeSlots(calendar) {
                        const view = calendar.view;
                        const start = view.currentStart;
                        const end = view.currentEnd;
                        const slotDuration = calendar.getOption('slotDuration');
                        const events = calendar.getEvents();
                        console.log(events);

                        const occupiedSlots = new Set();

                        // Parcourir tous les événements pour marquer les créneaux horaires occupés
                        for (let i = 0; i < events.length; i++) {
                            const event = events[i];
                            const eventStart = event.start;
                            const eventEnd = event.end || event.start; // Si l'événement n'a pas de fin, on considère qu'il dure 1 créneau horaire

                            const diff = (new Date(eventEnd) - new Date(eventStart)) / (1000 * 60 * slotDuration);
                            const slots = Array.from({ length: diff }).map(function(_, index) {
                                const slotTime = new Date(eventStart);
                                slotTime.setMinutes(slotTime.getMinutes() + index * slotDuration);
                                return slotTime.toISOString();
                            });

                            slots.forEach(function(slot) {
                                occupiedSlots.add(slot);
                            });
                        }

                        const availableSlots = [];

                        // Parcourir les créneaux horaires de début à fin et trouver les créneaux horaires non occupés
                        let currentSlot = new Date(start);
                        while (currentSlot < new Date(end)) {
                            const currentSlotISO = currentSlot.toISOString();
                            if (!occupiedSlots.has(currentSlotISO)) {
                                availableSlots.push(currentSlotISO);
                            }

                            currentSlot.setMinutes(currentSlot.getMinutes() + slotDuration);
                        }

                        console.log(availableSlots);
                    }

                    // Appel de la fonction pour trouver les plages horaires disponibles
                    findAvailableTimeSlots(calendar);



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


        allDaySlot: false,
        navLinks:true,
        selectable: true, // make able to select a slot

    })



    calendar.render()
    // findAvailableTimeSlots(calendar);
    // get all the events of full calendar

    // let events =  calendar.getEvents();
    //
    // console.log(events);




}