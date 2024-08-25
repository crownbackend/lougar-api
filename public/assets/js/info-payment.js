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
    console.log(data)
})