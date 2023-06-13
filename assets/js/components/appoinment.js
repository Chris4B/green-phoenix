import $ from 'jquery';


// Creation of  an appointment
window.onload = function(){
    let confirmBtn =  document.querySelector('#appointment-patient')
    let appointmentModal = document.querySelector('#appointmentModal')
    console.log(confirmBtn)
    confirmBtn.addEventListener('click', function (e){
        e.preventDefault()
        console.log('hey')

        //retrieve data from the modal
        

        $('#appointmentModal').modal('hide')

    })
}

