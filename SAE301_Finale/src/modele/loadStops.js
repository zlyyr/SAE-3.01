import { map } from "../vue/initMap.js";

export async function loadStops() {
  try {
    const response = await fetch("../modele/getStops.php");
    if (!response.ok) throw new Error("Erreur réseau");

    const stops = await response.json();

    stops.forEach((stop) => {
      if (stop.lat && stop.lon) {
        // vérifier que les coordonnées existent
        L.marker([stop.lat, stop.lon])
          .addTo(map)
          .bindPopup(`<b>${stop.name}</b><br>ID: ${stop.stop_id}`);
      }
    });
  } catch (err) {
    console.error("Erreur chargement parkings :", err);
  }
}
