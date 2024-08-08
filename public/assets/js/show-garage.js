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