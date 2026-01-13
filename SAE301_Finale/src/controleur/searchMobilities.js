import { loadStops } from "../modele/loadStops.js";

export async function getMobilitesAutourParking(parking, rayon = 500) {
  const stops = await loadStops();

  return stops
    .map((stop) => ({
      ...stop,
      distance: distanceMeters(
        parking.latitude,
        parking.longitude,
        stop.lat,
        stop.lon
      ),
    }))
    .filter((stop) => stop.distance <= rayon)
    .sort((a, b) => a.distance - b.distance);
}
