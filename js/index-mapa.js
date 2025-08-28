// Mapas de todas las barberías en index.php
document.addEventListener("DOMContentLoaded", function() {
    const mapDivs = document.querySelectorAll("[id^='map-']");

    mapDivs.forEach(div => {
        const lat = parseFloat(div.dataset.lat);
        const lng = parseFloat(div.dataset.lng);
        const nombre = div.dataset.nombre;

        const map = L.map(div.id, { scrollWheelZoom: false }).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

        L.marker([lat, lng]).addTo(map)
            .bindPopup(`<b>${nombre}</b><br><a href="https://www.google.com/maps/search/?api=1&query=${lat},${lng}" target="_blank">Ver en Google Maps</a>`);
    });
});
