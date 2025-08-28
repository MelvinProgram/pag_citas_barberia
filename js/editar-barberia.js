// Mapa y geocodificación interactiva para editar barbería
let editMapDiv = document.getElementById('map-edit');
let editMap, editMarker, editPopup;

if(editMapDiv){
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');
    const direccionInput = document.getElementById('direccion');

    const initialLat = parseFloat(latInput.value) || 40.4168; // Madrid por defecto
    const initialLng = parseFloat(lngInput.value) || -3.7038;

    // Inicializar mapa
    editMap = L.map('map-edit').setView([initialLat, initialLng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(editMap);

    // Crear marcador draggable
    editMarker = L.marker([initialLat, initialLng], { draggable: true }).addTo(editMap);

    // Crear popup inicial
    editPopup = editMarker.bindPopup(direccionInput.value || "Ubicación actual").openPopup();

    // Función para actualizar popup
    function updatePopup(address) {
        editMarker.getPopup().setContent(address).openOn(editMap);
    }

    // Actualizar inputs al arrastrar marcador
    editMarker.on('dragend', function(e){
        const pos = e.target.getLatLng();
        latInput.value = pos.lat;
        lngInput.value = pos.lng;
        updatePopup(`Lat: ${pos.lat.toFixed(5)}, Lng: ${pos.lng.toFixed(5)}`);
    });

    // Geocodificación mientras se escribe
    let typingTimer;
    const typingInterval = 800; // ms de espera

    direccionInput.addEventListener('input', function(){
        clearTimeout(typingTimer);
        typingTimer = setTimeout(geocodeAddress, typingInterval);
    });

    async function geocodeAddress() {
        const address = direccionInput.value.trim();
        if(!address) return;

        const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`);
        const data = await response.json();
        if(data.length > 0){
            const lat = parseFloat(data[0].lat);
            const lon = parseFloat(data[0].lon);

            latInput.value = lat;
            lngInput.value = lon;

            editMarker.setLatLng([lat, lon]);
            updatePopup(address);
            editMap.setView([lat, lon], 18);
        } else {
            updatePopup("Dirección no encontrada");
        }
    }

    // Al cargar, actualizar popup
    updatePopup(direccionInput.value);
}
