// Create an appointment

const confirmBtn = document.querySelector('#appointment-patient')

confirmBtn.addEventListener('click', function (event){
    event.preventDefault();

    // retrieve data

    const data = document.querySelector('input[name="appointmentData"]').value;
    console.log(data)
})
