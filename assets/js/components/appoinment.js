import $ from 'jquery';

window.onload = function(){

    // Creation of  an appointment for the patient

    let confirmBtn =  document.querySelector('#appointment-patient')
    let appointmentModal = document.querySelector('#appointmentUserModal')
    console.log(confirmBtn)
    confirmBtn.addEventListener('click', function (e){
        e.preventDefault()



        // //retrieve data from the modal
        let dataInput = document.querySelector('input[name="appointmentData"]')
        let doctorId = document.querySelector('input[name="doctor-id"]')
        let appointmentDate = dataInput.value
        let appointmentDoctor = doctorId.value
        let objectData = {
            'datestr': appointmentDate,
            'doctorId': appointmentDoctor
        }

        console.log('Données de rendez-vous:', objectData);

        //send Data to the server
        fetch('http://green-phoenix.test/event/users/new',{
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(objectData)
        })
            .then(response=>response.json())
            .then(ResponseData=>{

            })
        // Refresh the modal
        dataInput.value = '';
        $('#appointmentUserModal').modal('hide')


    })



// MODIFICATION OF AN APPOINTMENT


    let modifyBtn = document.querySelector('#modify-appointment-btn')
    console.log(modifyBtn)
    modifyBtn.addEventListener('click', function(event){
        event.preventDefault()

        //Retrieve data from the modal

        let modifiedData = document.querySelector('input[name="modified-date"]')
        let eventId = document.querySelector('#modifyEventModal').getAttribute('data-event-id')

        //create modified event object
        let modifiedEvent = {
            datestr: modifiedData.value,
            eventId: eventId
        }
console.log(modifiedEvent)
        //send data to the server

        fetch('http://green-phoenix.test/event/users/'+eventId+'edit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(modifiedEvent)
        })
        modifiedData.value = '';
        $('.test').modal('hide')
    })




console.log('end de js')

}

