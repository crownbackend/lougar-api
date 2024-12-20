let timeout = null;
const city = document.querySelector('#garage_city_name')
const postalCode = document.querySelector('#garage_city_postalCode')
document.querySelector('#garage_images').removeAttribute('required')
const imageContent = document.querySelector('#images_all')
const availabilityContent = document.querySelector('#availability_content')
const availabilityShowContent = document.querySelector('#availability_show_content')

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

const mapInfo = document.querySelector('#active-service')
const long = mapInfo.getAttribute('data-city-long')
const lat = mapInfo.getAttribute('data-city-lat')
const postal = mapInfo.getAttribute('data-city-postal')
const name = mapInfo.getAttribute('data-city-name')

var map = L.map('map').setView([lat, long], 13);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var marker;

var latLng = [lat, long];
map.setView(latLng, 13);

// Ajouter un marqueur à la nouvelle position
if (marker) {
    map.removeLayer(marker);
}
marker = L.marker(latLng).addTo(map)
    .bindPopup(name + ' (' + postal + ')')
    .openPopup();

function performSearch(query) {
    if (query.length < 3) {
        document.querySelector('#city_content').style.display = 'none'
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
        .catch(() => alert('Erreur serveur'));
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

// delete image  and set principal
function deleteImage(imageId) {
    if (confirm('Êtes vous sur de supprimer cette image ?')) {
        // Implement the AJAX request to delete the image
        fetch(`/image/${imageId}/delete`, {
            method: 'DELETE',
        })
            .then(response => response.text())
            .then(data => {
                imageContent.innerHTML = data
            })
            .catch(error => {
                alert('Erreur de suppréssion');
            });
    }
}

function setPrincipal(imageId) {
    if (confirm('Êtes vous sur de mettre cette image en tant que image princpale ?')) {
        fetch(`/image/${imageId}/principal`, {
            method: 'POST',
        })
            .then(response => response.text())
            .then(data => {
                imageContent.innerHTML = data
            })
            .catch(error => {
                alert('Erreur de suppréssion');
            });
    }
}

function deleteAvailabilityTime(id) {

    if (confirm('Êtes vous sur de supprimer ce créneaux ?')) {
        fetch(`/availability/time/delete/${id}`, {
            method: 'POST',
        }).then(response => response.text())
        .then(data => {
            availabilityShowContent.innerHTML = data
            window.location.reload()
        })
    }
}



$(document).ready(function () {

    if ($('#datetimepickershow_start').length > 0) {
        $('#datetimepickershow_start').datetimepicker({
            locale: 'fr',
            inline: true,
            sideBySide: true,
            format: 'DD-MM-YYYY',
            icons: {
                up: "fas fa-angle-up",
                down: "fas fa-angle-down",
                next: 'fas fa-angle-right',
                previous: 'fas fa-angle-left'
            }
        });

    }

    if ($('#datetimepickershow_end').length > 0) {
        $('#datetimepickershow_end').datetimepicker({
            locale: 'fr',
            inline: true,
            sideBySide: true,
            format: 'DD-MM-YYYY',
            icons: {
                up: "fas fa-angle-up",
                down: "fas fa-angle-down",
                next: 'fas fa-angle-right',
                previous: 'fas fa-angle-left'
            }
        });
        $('#datetimepickershow_end').data("DateTimePicker").clear();
    }

    $('#submit-btn').on('click', function(e) {
        e.preventDefault();

        let availabilityData = [];
        let startDate = null;
        let endDate = null;

        // Get start and end dates
        if ($('#datetimepickershow_start').length > 0) {
            startDate = $('#datetimepickershow_start').data('DateTimePicker').date();
        }
        if ($('#datetimepickershow_end').length > 0) {
            endDate = $('#datetimepickershow_end').data('DateTimePicker').date();
        }

        // Iterate over each day section
        $('.availability-sec .day-info').each(function() {
            let dayOfWeek = $(this).data('day-of-week');
            let times = [];

            $(this).find('.day-cont').each(function() {
                let startTime = $(this).find('input').eq(0).val();
                let endTime = $(this).find('input').eq(1).val();
                times.push({
                    start: startTime,
                    end: endTime
                });
            });

            if (times.length > 0) {
                availabilityData.push({
                    day: dayOfWeek,
                    times: times
                });
            }
        });

        const idGarage = $('#submit-btn').attr('data-garage-id');
        $.ajax({
            url: '/availability/update/' + idGarage,
            method: 'POST',
            data: JSON.stringify({
                startDate: startDate ? startDate.format('DD-MM-YYYY') : null,
                endDate: endDate ? endDate.format('DD-MM-YYYY') : null,
                availability: availabilityData
            }),
            contentType: 'application/json',
            success: function(response) {

                availabilityContent.innerHTML = response

                window.location.reload()
            },
            error: function(response) {
                alert("Erreur serveur !");
            }
        });
    });

    $("#delete_availability").on("click", function (e) {

        let availabilityId = $(this).data('availability-id');

        e.preventDefault();
        Swal.fire({
            title: "Êtes vous sur ?",
            text: "Vous allez supprimer les disponibilités !",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Oui, supprimer",
            cancelButtonText: "Non, annuler",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1,
        }).then(function (t) {
            if (t.isConfirmed) {
                window.location.href = "/availability/delete/" + availabilityId;
            } else {
                return false;
            }
        });
    })

});
