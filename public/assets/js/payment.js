const stripe = Stripe('pk_test_51PJYbuRvo2rcNwqWxGfh4D6xYhLYcbHdSoPQqFWdsnMuOHaiYHtNW9KNAiGjqlqX5OfbpTcEzSyQkYT2XKLK6opc00byX8923M'); // Remplacez par votre clé publique
const bookingInfo = document.querySelector("#booking_info")
async function getClientSecret() {
    let response = await fetch('/reservation/create-setup-intent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    return await response.json();
}

const response = getClientSecret()

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

response.then(clientSecret => {

// Personnalisation du thème de Stripe Elements
    const appearance = {
        theme: 'stripe',
    };

    const elements = stripe.elements({ clientSecret, appearance });
    const paymentElement = elements.create('card');
    paymentElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        // Confirmer la sauvegarde de la carte
        const { error, setupIntent } = await stripe.confirmCardSetup(clientSecret, {
            payment_method: {
                card: paymentElement,
            },
        });

        if (error) {
            document.getElementById('error-message').textContent = error.message;
        } else if (setupIntent.status === 'succeeded') {
            // La carte a été sauvegardée avec succès
            console.log(setupIntent)
            const data = {
                reservationInfo: bookingInfo.getAttribute('data-info'),
                methodPayment: setupIntent.payment_method,
                garageId: bookingInfo.getAttribute('data-garageId')
            }

            const responseReservation = createReservation(data)
            responseReservation.then(res => {
                console.log(res)
            })
        }
    });
})
