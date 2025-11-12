<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Sparking 🚗</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="style.css" />
</head>
<body>
<header>Sparking 🚘</header>

<div id="searchBar">
    <input type="text" id="parkingName" placeholder="Rechercher un parking...">
    <button id="goButton">Aller</button>
</div>

<div id="map"></div>

<footer>© 2025 Sparking — L’étincelle qui te guide vers ta place</footer>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
// Initialisation de la carte sur Metz
const map = L.map('map').setView([49.1193, 6.1757], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution:'© OpenStreetMap',
    maxZoom:19
}).addTo(map);

// Marqueur voiture pour l'utilisateur
let userMarker = null;
if (navigator.geolocation) {
    navigator.geolocation.watchPosition(
        pos => {
            const lat = pos.coords.latitude, lon = pos.coords.longitude;
            if(userMarker) userMarker.setLatLng([lat, lon]);
            else {
                userMarker = L.marker([lat, lon], {
                    icon: L.icon({iconUrl:'https://cdn-icons-png.flaticon.com/512/684/684908.png', iconSize:[32,32]})
                }).addTo(map).bindPopup("Vous êtes ici");
                map.setView([lat, lon],15);
            }
        },
        err => console.log("Géolocalisation refusée ou erreur")
    );
}

async function goToParking(p) {
    if(!userMarker) {
        alert("Position utilisateur non disponible !");
        return;
    }

    const start = userMarker.getLatLng();
    const end = L.latLng(p.lat, p.lon);

    const apiKey = "eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6IjUxMzM5ZDI3OTNmZDQ5ODNhMDQ4MDllZDc5MGVlNjA2IiwiaCI6Im11cm11cjY0In0="; // <- remplace par ta clé

    const url = `https://api.openrouteservice.org/v2/directions/driving-car?api_key=${apiKey}&start=${start.lng},${start.lat}&end=${end.lng},${end.lat}`;

    try {
        const res = await fetch(url);
        const data = await res.json();

        // Supprimer l'ancien itinéraire si existant
        if(window.currentRoute) map.removeLayer(window.currentRoute);

        const coords = data.features[0].geometry.coordinates.map(c => [c[1], c[0]]);
        window.currentRoute = L.polyline(coords, {color:'blue', weight:5}).addTo(map);

        // Zoom sur le trajet
        map.fitBounds(window.currentRoute.getBounds());

    } catch(err) {
        console.log("Erreur itinéraire:", err);
        alert("Impossible de calculer l'itinéraire.");
    }
}

// Gestion des parkings
let parkingMarkers = [];
function loadParkings() {
    fetch('parkings.json')
    .then(r => r.json())
    .then(data => {
        // Supprime anciens marqueurs
        parkingMarkers.forEach(m => map.removeLayer(m));
        parkingMarkers = [];

        data.forEach(p => {
            const marker = L.marker([p.lat, p.lon], {
                icon: L.icon({iconUrl:'location.png', iconSize:[32,32]})
            }).addTo(map);

            const percent = (p.available / p.capacity) * 100;
            let color;
            if(p.available === 0) color = "black";
            else if(percent <= 10) color = "red";
            else if(percent <= 25) color = "orange";
            else if(percent <= 50) color = "#DED564";
            else if(percent <= 80) color = "lightblue";
            else color = "blue";

            const popupContent = `
                <b>${p.name}</b><br>
                Capacité : ${p.capacity} places<br>
                Disponibles : <span style="color:${color}; font-weight:600">${p.available}</span> places<br>
                <a href="https://www.google.com/maps/dir/?api=1&destination=${p.lat},${p.lon}" target="_blank"> S'y rendre 🚗</button>`;
                //<a href="https://www.google.com/maps/dir/?api=1&destination=${p.lat},${p.lon}" target="_blank"> S'y rendre 🚗</a>`
                //Pour utiliser google maps
            marker.bindPopup(popupContent);

            parkingMarkers.push(marker);
        });
    })
    .catch(err => console.log("Erreur chargement parkings:", err));
}

// Recherche par nom
document.getElementById('goButton').addEventListener('click', () => {
    const name = document.getElementById('parkingName').value.toLowerCase();
    const parking = parkingMarkers.find(m => m.getPopup().getContent().toLowerCase().includes(name));
    if(parking){
        map.setView(parking.getLatLng(), 17);
        parking.openPopup();
    } else {
        alert("Parking non trouvé !");
    }
});

// Charge au démarrage
loadParkings();
</script>
</body>
</html>
