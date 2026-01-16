import { map } from "../vue/initMap.js";

export let londonParkingList = [];

let londonCluster = null;

//Supprimer les parkings de Londres
export function clearLondonParkingMarkers() {
  if (londonCluster) {
    map.removeLayer(londonCluster);
    londonCluster = null;
  }
  londonParkingList = [];
}

//Charger les parkings de Londres
export async function loadLondonParkings() {
  //Sécurité : ne pas recharger 2 fois les parkings
  if (londonCluster) return;

  //Bounding box : centre de Londres
  const overpassQuery = `
    [out:json];
    (
      node["amenity"="parking"](51.48,-0.20,51.54,-0.05);
      way["amenity"="parking"](51.48,-0.20,51.54,-0.05);
    );
    out center;
  `;

  try {
    //Création du cluster des markers
    londonCluster = L.markerClusterGroup();

    //Envoi de la requête POST à l’API Overpass
    const response = await fetch("https://overpass-api.de/api/interpreter", {
      method: "POST",
      body: overpassQuery,
    });

    const data = await response.json();

    //Récupération des coordonnées
    data.elements.forEach((el) => {
      const lat = el.lat || el.center?.lat;
      const lon = el.lon || el.center?.lon;
      if (!lat || !lon) return;

      const name = el.tags?.name || "Parking";
      const capacity = el.tags?.capacity || "Inconnue";

      //On ignore les parkings sans capacité ou trop petits
      if (!capacity || isNaN(capacity) || capacity < 10) return;

      //Création du marker Leaflet
      const marker = L.marker([lat, lon], {
        icon: L.divIcon({
          className: "parking-marker",
          html: "<div></div>",
          iconSize: [24, 24],
          iconAnchor: [12, 12],
        }),
      });

      londonParkingList.push({
        lat,
        lon,
        name,
        capacity,
      });

      //Contenu du popup
      const popup = `
        <b>${name}</b><br>
        Capacité : <b>${capacity}</b><br>
        <button class="go-btn" onclick="goToParking(${lat}, ${lon})">
          M'y amener
        </button><br>
        <a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lon}"
           target="_blank">
          S'y rendre avec Google Maps
        </a>
      `;

      marker.bindPopup(popup);
      londonCluster.addLayer(marker);
    });

    //Ajout de tous les markers sur la carte
    map.addLayer(londonCluster);

    console.log(`Parkings Londres chargés : ${londonParkingList.length}`);
  } catch (err) {
    console.error("Erreur chargement parkings Londres :", err);
  }
}
