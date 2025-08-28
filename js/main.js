document.addEventListener("DOMContentLoaded", function() {
    // Inicializar mapa centrado en Madrid
    let map = L.map('map').setView([40.4168, -3.7038], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);

    let marker;

    // Geocodificar dirección al cambiar input
    const direccionInput = document.getElementById('direccion');
    if(direccionInput){
        direccionInput.addEventListener('change', async function() {
            const address = this.value;
            if(!address) return;

            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
            const data = await response.json();
            if(data.length > 0){
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);

                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lon;

                if(marker) map.removeLayer(marker);
                marker = L.marker([lat, lon]).addTo(map);
                map.setView([lat, lon], 16);
            }
        });
    }
});
