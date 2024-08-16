const city = document.querySelector('#search_city')
let timeout = null;

city.addEventListener('keyup', function() {
    clearTimeout(timeout);

    timeout = setTimeout(function() {
        performSearch(city.value);
    }, 500); // délai de 500 ms
});


function performSearch(query) {
    if (query.length < 3) {
        document.querySelector('#city_content').style.display = 'none'
        document.querySelector('#city_id').value = ''
        return
    }

    fetch(`/city/search?query=${query}`)
        .then(response => response.json())
        .then(data => {
            let resultsHtml = ''
            if(data.length > 0) {
                data.forEach(v => {
                    const vJson = JSON.stringify(v).replace(/"/g, '&quot;');
                    resultsHtml += '<p style="cursor: pointer" onclick="selectCity('+vJson+')"><i class="feather-map-pin"></i>' + v.name + ' (' + v.postalCode +')</p>'
                });
            } else {
                resultsHtml += '<p><i class="feather-map-pin"></i>Aucun résultat </p>'
            }

            document.getElementById('result_city').innerHTML = resultsHtml
            document.querySelector('#city_content').style.display = 'block'
        })
        .catch(error => console.error('Error:', error));
}

function selectCity(value) {
    city.value = value.name
    document.querySelector('#city_id').value = value.id
    document.querySelector('#city_content').style.display = 'none'
}