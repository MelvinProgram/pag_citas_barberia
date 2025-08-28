// Mapa en la página de reserva.php
document.addEventListener("DOMContentLoaded", function() {
    const mapDiv = document.getElementById('map');
    if (!mapDiv) return;

    const lat = parseFloat(mapDiv.dataset.lat);
    const lng = parseFloat(mapDiv.dataset.lng);
    const nombre = mapDiv.dataset.nombre;

    const map = L.map('map').setView([lat, lng], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup(`<b>${nombre}</b><br><a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">Ver en Google Maps</a>`)
        .openPopup();
});
