import $ from 'jquery';


// Creation of  an appointment
window.onload = function(){
    let confirmBtn =  document.querySelector('#appointment-patient')
    let appointmentModal = document.querySelector('#appointmentUserModal')
    console.log(confirmBtn)
    confirmBtn.addEventListener('click', function (e){
        e.preventDefault()

        // //retrieve data from the modal
        // let data = document.querySelector('input[name="appointmentData"]').value
        // fetch('http://green-phoenix.test/event/users/new',{
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json'
        //     },
        //     body: JSON.stringify(data)
        // })
        //     .then(response=>response.json())
        //     .then(ResponseData=>{
        //
        //     })
        $('#appointmentUserModal').modal('hide')




        let appointmentModal = document.querySelector('#appointmentUserModal')
        appointmentModal.value = ''

        // $('#appointmentUserModal').on('hidden.modal', function(){
        //     data.val('')
        // })
    })
}

