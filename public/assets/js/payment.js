const stripe = Stripe('pk_test_51PJYbuRvo2rcNwqWxGfh4D6xYhLYcbHdSoPQqFWdsnMuOHaiYHtNW9KNAiGjqlqX5OfbpTcEzSyQkYT2XKLK6opc00byX8923M'); // Remplacez par votre clé publique
const elements = stripe.elements();

// Personnalisation du thème de Stripe Elements
const appearance = {
    theme: 'stripe',
    variables: {
        colorPrimary: '#6772e5',
        colorBackground: '#f6f9fc',
        colorText: '#32325d',
        colorDanger: '#df1b41',
        fontFamily: 'Helvetica, Arial, sans-serif',
        spacingUnit: '2px',
        borderRadius: '4px',
    },
};

const cardElement = elements.create('card', { appearance });
cardElement.mount('#card-element');

const form = document.getElementById('payment-form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();

    let response = await fetch('/reservation/create-setup-intent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
    });

    let clientSecret = await response.json();

    // Confirmer la sauvegarde de la carte
    const { error, setupIntent } = await stripe.confirmCardSetup(clientSecret, {
        payment_method: {
            card: cardElement,
        },
    });

    if (error) {
        document.getElementById('error-message').textContent = error.message;
    } else if (setupIntent.status === 'succeeded') {
        // La carte a été sauvegardée avec succès
        console.log(setupIntent)
        // Enregistrez l'ID du payment_method pour les paiements futurs
    }
});