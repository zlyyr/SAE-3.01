import { initMap } from "./vue/initMap.js";
import { initGeolocation } from "./modele/geoLoc.js";
import { loadParkings } from "./modele/loadParkings.js";
import { loadLondonParkings } from "./modele/loadParkingsLondon.js";
import { initSearch } from "./controleur/search.js";
import { initNearest } from "./controleur/searchNearest.js";
import { getMobilitesAutourParking } from "./controleur/searchMobilities.js";
import { map } from "./vue/initMap.js";

const city = globalThis.userCity;

// Initialisation
initMap();
initGeolocation(city);
loadParkings();
loadLondonParkings();
initSearch();
initNearest();

// 🔹 Layer pour les arrêts de bus
const mobilitesLayer = L.layerGroup().addTo(map);

// 🔹 Fonction globale appelée depuis les popups
globalThis.showMobilites = async (lat, lon, name) => {
  mobilitesLayer.clearLayers();

  const parking = {
    latitude: lat,
    longitude: lon,
    name
  };

  const stops = await getMobilitesAutourParking(parking, 500);

  stops.forEach(stop => {
    const marker = L.circleMarker([stop.lat, stop.lon], {
      radius: 6,
      color: "#0066ff",
      fillOpacity: 0.8
    });

    marker.bindPopup(`
      <b>${stop.name}</b><br>
      📍 ${Math.round(stop.distance)} m
    `);

    mobilitesLayer.addLayer(marker);
  });
};

// 🔹 Nettoyage quand on annule le trajet
const arretBtn = document.getElementById("arretTrajet");
arretBtn.addEventListener("click", () => {
  mobilitesLayer.clearLayers();
});
