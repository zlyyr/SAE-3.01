import { map } from "../vue/initMap.js";
import { getMobilitesAutourParking } from "./searchMobilities.js";
import { parkingList } from "../modele/loadParkings.js";

let stopsLayer = null;

export function initMobilities() {

  stopsLayer = L.layerGroup().addTo(map);

  window.showMobilites = async (parkingId) => {

    stopsLayer.clearLayers();

    const parking = parkingList.find(p => p.id === parkingId);
    if (!parking) return;

    const stops = await getMobilitesAutourParking(
      {
        lat: parking.lat,
        lon: parking.lon
      },
      500
    );

    stops.forEach(stop => {

      
      L.circleMarker([stop.lat, stop.lon], {
        radius: 6,
        color: "blue",
        fillOpacity: 0.9
      })
      .bindPopup(`${stop.name}<br>${Math.round(stop.distance)} m`)
      .addTo(stopsLayer);

    });
  };
}
