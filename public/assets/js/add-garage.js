let timeout = null;
const city = document.querySelector('#garage_city_name')
const postalCode = document.querySelector('#garage_city_postalCode')
city.addEventListener('keyup', function() {
    clearTimeout(timeout);

    timeout = setTimeout(function() {
        const query = document.querySelector('#garage_city_name').value;
        performSearch(query);
    }, 500); // délai de 500 ms
});

postalCode.addEventListener('keyup', function() {
    clearTimeout(timeout);

    timeout = setTimeout(function() {
        const query = document.querySelector('#garage_city_postalCode').value;
        performSearch(query);
    }, 500); // délai de 500 ms
});

var map = L.map('map').setView([48.9527032, 2.3911093333333], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var marker;

function performSearch(query) {
    if (query.length < 3) {
        document.querySelector('#city_content').style.display = 'none'
        return
    }
    console.log('Performing search for:', query);

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
    postalCode.value = value.postalCode
    document.querySelector('#garage_city_id').value = value.id
    document.querySelector('#city_content').style.display = 'none'

    // Mettre à jour la vue de la carte
    var latLng = [value.latitude, value.longitude];
    map.setView(latLng, 13);

    // Ajouter un marqueur à la nouvelle position
    if (marker) {
        map.removeLayer(marker);
    }
    marker = L.marker(latLng).addTo(map)
        .bindPopup(value.name + ' (' + value.postalCode + ')')
        .openPopup();
}

document.getElementById('garage_images').addEventListener('change', function(event) {
    const imagePreview = document.getElementById('image_preview');
    imagePreview.innerHTML = ''; // Clear previous images
    const files = event.target.files;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
            const li = document.createElement('li');
            li.innerHTML = `
                    <div class="img-preview">
                        <img src="${e.target.result}" alt="Selected Image">
                        <a href="javascript:void(0);" class="remove-gallery" onclick="removeImage(this)"><i class="feather-trash-2"></i></a>
                    </div>
                    <label class="custom_check">
                        <input type="radio" name="gallery" value="${file.name}" onclick="setDefaultImage('${file.name}')">
                        <span class="checkmark"></span>
                    </label>
                `;
            imagePreview.appendChild(li);
        };

        reader.readAsDataURL(file);
    }
});

function removeImage(element) {
    element.closest('li').remove();
}

function setDefaultImage(imageName) {
    document.getElementById('garage_defaultImage').value = imageName;
}