import { map } from "../vue/initMap.js";
import { getMobilitesAutourParking } from "./searchMobilities.js";

let mobilitesLayer = null;

export function initMobilities() {
  mobilitesLayer = L.layerGroup().addTo(map);

  // fonction globale UNIQUEMENT pour le popup

  globalThis.showMobilites = async (lat, lon, btn) => {
    const parking = {
      latitude: lat,
      longitude: lon,
    };

    const stops = await getMobilitesAutourParking(parking, 500);

    let html = "<b>🚏 Arrêts à proximité :</b><br>";

    if (!stops || stops.length === 0) {
      html += "Aucun arrêt trouvé à moins de 500 m.";
    } else {
      stops.slice(0, 5).forEach((stop) => {
        html += `• ${stop.name} (${Math.round(stop.distance)} m)<br>`;
      });
    }

    const popupDiv = btn.closest(".leaflet-popup-content");
    popupDiv.innerHTML += `<div class="stops-list">${html}</div>`;
  };

  // nettoyage si on annule le trajet
  const btn = document.getElementById("arretTrajet");
  btn?.addEventListener("click", () => {
    mobilitesLayer.clearLayers();
  });
}
