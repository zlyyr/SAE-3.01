import { map } from "../vue/initMap.js";

export let parkingList = [];
let parkingClusterGroup = null;

// Supprimer les marqueurs
export function clearParkingMarkers() {
  if (parkingClusterGroup) {
    map.removeLayer(parkingClusterGroup);
  }
  parkingClusterGroup = L.markerClusterGroup();
}

export function loadParkings() {
  fetch("../modele/getParking.php")
    .then((r) => r.json())
    .then((data) => {
      clearParkingMarkers();
      parkingList = [];

      parkingClusterGroup = L.markerClusterGroup({ maxClusterRadius: 120 });

      data.forEach((p) => {
        const marker = L.marker([p.lat, p.lon], {
          icon: L.icon({
            iconUrl: "./images/location.png",
            iconSize: [32, 32],
          }),
        });

        const popup = `
          <b>${p.name}</b><br>
          Adresse : ${p.adresse ?? "?"}<br>
          Capacité : <b>${p.nb_places}</b><br>
          Libres : <b>${p.place_libre ?? "?"}</b><br>
          PMR : ${p.nb_pmr}<br>
          Vélo : ${p.nb_velo}<br>
          Électrique : ${p.nb_voitures_electriques}<br>
          Tarif 1h : ${p.tarif_1h} €<br>
          <button onclick="goToParking(${p.lat}, ${p.lon})">M'y amener</button>
        `;

        marker.bindPopup(popup);
        parkingClusterGroup.addLayer(marker);
        parkingList.push(p);
      });

      map.addLayer(parkingClusterGroup);
    })
    .catch((err) =>
      console.error("Erreur chargement parkings fusionnés :", err)
    );
}
