import { map } from "../vue/initMap.js";

let stopsLayer = null;

export async function showMobilites(lat, lon, name) {
  try {
    // retire l'ancien layer de stops si existant
    if (stopsLayer) {
      map.removeLayer(stopsLayer);
    }

    const response = await fetch("../modele/getStops.php");
    if (!response.ok) throw new Error("Erreur chargement stops");

    const stops = await response.json();

    // filtre les stops proches (exemple rayon 500m)
    const nearbyStops = stops.filter(
      (s) => Math.hypot(s.lat - lat, s.lon - lon) * 111000 < 500 // approximation en mètres
    );

    stopsLayer = L.layerGroup();

    nearbyStops.forEach((s) => {
      L.marker([s.lat, s.lon])
        .bindPopup(`<b>${s.stop_name}</b><br>ID: ${s.stop_id}`)
        .addTo(stopsLayer);
    });

    stopsLayer.addTo(map);

    // recentre la map sur le parking
    map.setView([lat, lon], 16);
  } catch (err) {
    console.error("Erreur affichage arrêts :", err);
  }
}
