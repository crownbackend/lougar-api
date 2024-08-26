async function getClientSecret() {
    let response = await fetch('/reservation/get-card', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    return await response.json();
}

const response = getClientSecret()

response.then(data => {
    const brand = document.querySelector("#brand")
    const last4 = document.querySelector("#last4")
    const exp_month = document.querySelector("#exp_month")
    const exp_year = document.querySelector("#exp_year")
    brand.innerHTML = data.brand
    last4.innerHTML = data.last4
    exp_month.innerHTML = data.exp_month
    exp_year.innerHTML = data.exp_year
})

const bookingInfo = document.querySelector("#booking_info")
const sendInfo = document.querySelector("#submit")

async function createReservation(data) {
    let response = await fetch('/reservation/create-reservation', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    });

    return await response.json();
}

sendInfo.addEventListener('click', function (e) {
    e.preventDefault()
    const data = {
        reservationInfo: bookingInfo.getAttribute('data-info'),
        garageId: bookingInfo.getAttribute('data-garageId')
    }

    const responseReservation = createReservation(data)
    responseReservation.then(res => {
        window.location.href = '/reservation/success/booking/' + res
    }).catch(() => {
        alert('Erreur serveur')
    })
})