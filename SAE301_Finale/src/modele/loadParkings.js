import { map } from "../vue/initMap.js";

export let parkingList = [];
let parkingClusterGroup = [];

//On supprime les marqueurs des parkings
export function clearParkingMarkers() {
  if (parkingClusterGroup) {
    map.removeLayer(parkingClusterGroup);
  }
  parkingClusterGroup = L.markerClusterGroup(); // réinitialisation
}

export function loadParkings() {
  const wfsUrl =
    "https://maps.eurometropolemetz.eu/public/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=public:pub_tsp_sta&srsName=EPSG:4326&outputFormat=application/json&cql_filter=id%20is%20not%20null";

  //On prend les données de l'API et on les mets en json dans un tableau
  fetch(wfsUrl)
    .then((r) => r.json())
    .then((data) => {
      clearParkingMarkers();
      parkingList = [];
      parkingClusterGroup = L.markerClusterGroup({
        maxClusterRadius: 120,
      });

      data.features.forEach((f) => {
        const [lon, lat] = f.geometry.coordinates;
        const p = f.properties;

        //ON initialise la marqueur personnalisé pour les parkings
        const marker = L.marker([lat, lon], {
          icon: L.icon({
            iconUrl: "./images/location.png",
            iconSize: [32, 32],
          }),
        });
        parkingClusterGroup.addLayer(marker);

        //Objet parking avec les bons noms comme dans l'API
        const parking = {
          lat,
          lon,
          name: p.lib,
          free: p.place_libre,
        };

        parkingList.push(parking);

        //Contenu du popup de chaque parking
        const popup = `
            <b>${p.lib}</b><br>
            Type : ${p.typ}<br>
            Capacité totale : <b>${p.place_total}</b><br>
            Places libres : <b>${p.place_libre}</b><br>
            <button class="go-btn" onclick="goToParking(${lat}, ${lon})">
                M'y amener
            </button><br>
            <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lon}" target="_blank">S'y rendre avec Google Maps</a>`;

        //On lie à chaque marqueur son popup
        marker.bindPopup(popup);
        parkingClusterGroup.addLayer(marker);
      });
      map.addLayer(parkingClusterGroup);
    })

    //Erreur si on récupère pas les données de l'API
    .catch((err) => console.error("Erreur chargement parkings API :", err));
}
