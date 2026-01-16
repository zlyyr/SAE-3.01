import { map } from "../vue/initMap.js";
import { staticParkings } from "../vue/data/data.js";

//Liste globale des parkings de Metz
export let parkingList = [];
let parkingClusterGroup = null;

async function fetchApiData() {
  try {
    const apiUrl = "https://maps.eurometropolemetz.eu/public/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=public:pub_tsp_sta&srsName=EPSG:4326&outputFormat=application/json&cql_filter=id%20is%20not%20null";
    const response = await fetch(apiUrl);
    const apiData = await response.json();
    const byId = {};
    const byName = {};
    apiData.features.forEach(f => {
      const p = f.properties;
      if (p.id) byId[p.id] = p;
      if (p.lib) byName[p.lib] = p;
    });
    return { byId, byName };
  } catch (err) {
    console.error("Erreur API:", err);
    return { byId: {}, byName: {} };
  }
}

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

//Chargement des parkings depuis données statiques et API
export async function loadParkings() {
  const { byId } = await fetchApiData();
  const out = staticParkings.map(p => {
    const api = p.id_api ? byId[p.id_api] : null;
    return {
      ...p,
      lat: parseFloat(p.lat),
      lon: parseFloat(p.lon),
      tarif_1h: parseFloat(p.tarif_1h),
      tarif_2h: parseFloat(p.tarif_2h),
      tarif_24h: parseFloat(p.tarif_24h),
      nb_places: parseInt(p.nb_places),
      nb_pmr: parseInt(p.nb_pmr),
      nb_velo: parseInt(p.nb_velo),
      nb_voitures_electriques: parseInt(p.nb_voitures_electriques),
      place_libre: api ? api.place_libre : undefined
    };
  });
  parkingList = out;
  displayParkings(parkingList);
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
