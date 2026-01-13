import { map } from "../vue/initMap.js";
import { getMobilitesAutourParking } from "./searchMobilities.js";

let mobilitesLayer = null;

export function initMobilities() {
  mobilitesLayer = L.layerGroup().addTo(map);

  // fonction globale UNIQUEMENT pour le popup
  globalThis.showMobilites = async (lat, lon) => {
    console.log("showMobilites appelée", lat, lon);
    alert("CLICK OK");
  
    mobilitesLayer.clearLayers();
  
    const parking = {
      latitude: lat,
      longitude: lon
    };
  
    const stops = await getMobilitesAutourParking(parking, 500);
    console.log("stops:", stops);

    stops.forEach(stop => {
      L.circleMarker([stop.lat, stop.lon], {
        radius: 6,
        color: "#0066ff",
        fillOpacity: 0.8
      })
        .bindPopup(
          `<b>${stop.name}</b><br>📍 ${Math.round(stop.distance)} m`
        )
        .addTo(mobilitesLayer);
    });
  };

  // nettoyage si on annule le trajet
  const btn = document.getElementById("arretTrajet");
  btn?.addEventListener("click", () => {
    mobilitesLayer.clearLayers();
  });
}
