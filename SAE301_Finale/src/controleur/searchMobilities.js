import { loadStops } from "../modele/loadStops.js";
import { distance } from "../modele/utils.js";

export async function getMobilitesAutourParking(parking, rayon = 500) {
  const stops = await loadStops();

return stops
  .map(stop => {
    const slat = Number(stop.lat);
    const slon = Number(stop.lon);

    const d = distance(
      Number(parking.lat),
      Number(parking.lon),
      slat,
      slon
    );

    return {
      ...stop,
      distance: d
    };
  })
  .filter(stop => Number.isFinite(stop.distance) && stop.distance <= rayon)
  .sort((a, b) => a.distance - b.distance)
  .slice(0, 5);

}

