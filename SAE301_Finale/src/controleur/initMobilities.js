import { map } from "../vue/initMap.js";
import { getMobilitesAutourParking } from "./searchMobilities.js";

let mobilitesLayer = null;

export function initMobilities() {
  mobilitesLayer = L.layerGroup().addTo(map);

  // fonction globale UNIQUEMENT pour le popup
  globalThis.showMobilites = async (lat, lon) => {
    alert("CLICK OK", lat, lon);

    mobilitesLayer.clearLayers();

    const parking = {
      latitude: lat,
      longitude: lon,
    };

    const stops = await getMobilitesAutourParking(parking, 500);

    console.log("STOPS REÇUS :", stops);

    if (!stops || stops.length === 0) {
      alert("Aucun arrêt trouvé");
      return;
    }

    stops.forEach((stop) => {
      console.log("STOP:", stop);

      L.circleMarker([stop.lat, stop.lon], {
        radius: 6,
        color: "blue",
        fillOpacity: 1,
      })
        .bindPopup(stop.name ?? "Sans nom")
        .addTo(mobilitesLayer);
    });
  };

  // nettoyage si on annule le trajet
  const btn = document.getElementById("arretTrajet");
  btn?.addEventListener("click", () => {
    mobilitesLayer.clearLayers();
  });
}
