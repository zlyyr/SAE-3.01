<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Sparking 🚗</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
body { margin:0; font-family:'Poppins', sans-serif; display:flex; flex-direction:column; height:100vh; background:#f4f7f9; }
header { background:#0078ff; color:white; text-align:center; padding:1rem; font-size:1.8rem; font-weight:600; box-shadow:0 2px 10px rgba(0,0,0,0.2); }
#map { flex:1; width:100%; }
footer { background:#0078ff; color:white; text-align:center; padding:0.5rem; font-size:0.9rem; }
button { position:absolute; top:80px; right:10px; z-index:1000; padding:0.5rem 1rem; background:#0078ff; color:white; border:none; border-radius:5px; cursor:pointer; }
button:hover { background:#005dc1; }
</style>
</head>
<body>
<header>Sparking 🚘</header>
<div id="map"></div>
<button id="reload">Recharger les parkings</button>
<footer>© 2025 Sparking — L’étincelle qui te guide vers ta place.</footer>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
const map = L.map('map').setView([49.1193, 6.1757], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution:'© OpenStreetMap', maxZoom:19 }).addTo(map);

let userMarker = null;
if (navigator.geolocation) {
    navigator.geolocation.watchPosition(
        pos => {
            const lat=pos.coords.latitude, lon=pos.coords.longitude;
            if(userMarker) userMarker.setLatLng([lat, lon]);
            else {
                userMarker=L.marker([lat, lon], {icon:L.icon({iconUrl:'https://cdn-icons-png.flaticon.com/512/684/684908.png', iconSize:[32,32]})}).addTo(map).bindPopup("Vous êtes ici");
                map.setView([lat, lon],15);
            }
        },
        err => console.log("Géolocalisation refusée ou erreur")
    );
}

let parkingMarkers = [];

function loadParkings() {
    fetch('parkings.json')
    .then(r=>r.json())
    .then(data=>{
        // Supprime anciens marqueurs
        parkingMarkers.forEach(m=>map.removeLayer(m));
        parkingMarkers=[];
        data.forEach(p=>{
            const marker = L.marker([p.lat,p.lon], {icon:L.icon({iconUrl:'location.png', iconSize:[32,32]})
        }).addTo(map);
           
    
        const popup_cont = `<b>${p.name}</b><br>
                Capacité : ${p.capacity} places<br>
                Disponibles : ${p.available} places`;
        marker.bindPopup(popup_cont);
        parkingMarkers.push(marker);
        });
    })
    .catch(err=>console.log("Erreur chargement parkings:", err));
}

document.getElementById('reload').addEventListener('click', loadParkings);

// Charge au démarrage
loadParkings();
</script>
</body>
</html>
