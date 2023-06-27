import $ from 'jquery';

window.onload = function(){
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

}
