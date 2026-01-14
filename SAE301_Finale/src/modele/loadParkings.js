import { map } from "../vue/initMap.js";

export let parkingList = [];
let parkingClusterGroup = null;

function displayParkings(list) {
  if (parkingClusterGroup) {
    map.removeLayer(parkingClusterGroup);
  }

  parkingClusterGroup = L.markerClusterGroup({ maxClusterRadius: 120 });

  list.forEach((p) => {
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
      <button class="go-btn" onclick="goToParking(${p.lat}, ${
      p.lon
    })">M'y amener</button><br>
      <button class="go-btn" onclick="showMobilites(${p.lat}, ${p.lon}, this)">
  🚏 Arrêts à proximité
</button>
`;

    marker.bindPopup(popup);
    parkingClusterGroup.addLayer(marker);
  });

  map.addLayer(parkingClusterGroup);
}

export function loadParkings() {
  fetch("../modele/getParking.php")
    .then((r) => r.json())
    .then((data) => {
      parkingList = data;
      displayParkings(parkingList);
    })
    .catch((err) => console.error("Erreur chargement parkings :", err));
}

const cbVelo = document.getElementById("v");
const cbPMR = document.getElementById("pmr");
const cbElec = document.getElementById("e");
const cbPayant = document.getElementById("p");

[cbVelo, cbPMR, cbElec, cbPayant].forEach((cb) =>
  cb.addEventListener("change", applyFilters)
);

function applyFilters() {
  let filtered = parkingList;

  if (cbVelo.checked) {
    filtered = filtered.filter((p) => p.nb_velo > 0);
  }

  if (cbPMR.checked) {
    filtered = filtered.filter((p) => p.nb_pmr > 0);
  }

  if (cbElec.checked) {
    filtered = filtered.filter((p) => p.nb_voitures_electriques > 0);
  }

  if (cbPayant.checked) {
    filtered = filtered.filter((p) => p.tarif_1h == 0);
  }

  displayParkings(filtered);
}
