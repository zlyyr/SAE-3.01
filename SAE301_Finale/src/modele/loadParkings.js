import { map } from "../vue/initMap.js";

//Liste globale des parkings de Metz
export let parkingList = [];
let parkingClusterGroup = null;

function displayParkings(list) {
  if (parkingClusterGroup) {
    map.removeLayer(parkingClusterGroup);
  }

  //Création d’un groupe de clusters pour les parkings
  parkingClusterGroup = L.markerClusterGroup({ maxClusterRadius: 120 });

  //Création du marker avec une icône personnalisée
  list.forEach((p) => {
    const marker = L.marker([p.lat, p.lon], {
      icon: L.divIcon({
        className: "parking-marker",
        html: "<div></div>",
        iconSize: [24, 24],
        iconAnchor: [12, 12],
      }),
    });

    //Contenu HTML du popup du marker
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
      <button class="go-btn go-mobilites">🚏 Arrêts à proximité</button>
    `;

    //Association du popup au marker
    marker.bindPopup(popup);
    parkingClusterGroup.addLayer(marker);

    //Récupération du bouton dans le HTML du popup
    marker.on("popupopen", (e) => {
      const popupEl = e.popup.getElement();

      const btnMob = popupEl.querySelector(".go-mobilites");

      if (btnMob) {
        btnMob.addEventListener("click", () => {
          showMobilites(p.id);
        });
      }
    });
  });

  map.addLayer(parkingClusterGroup);
}

//Chargement des parkings depuis le serveur
export function loadParkings() {
  fetch("../modele/getParking.php")
    .then((r) => r.json())
    .then((data) => {
      parkingList = data;
      //Affichage sur la carte
      displayParkings(parkingList);
    })
    .catch((err) => console.error("Erreur chargement parkings :", err));
}

//Gestion des filtres
const cbVelo = document.getElementById("v");
const cbPMR = document.getElementById("pmr");
const cbElec = document.getElementById("e");
const cbPayant = document.getElementById("p");

//À chaque fois qu'on coche la case, on applique le filtre correspondant
[cbVelo, cbPMR, cbElec, cbPayant].forEach((cb) =>
  cb.addEventListener("change", applyFilters)
);

//Application des filtres
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

  //Réaffichage des parkings filtrés
  displayParkings(filtered);
}
