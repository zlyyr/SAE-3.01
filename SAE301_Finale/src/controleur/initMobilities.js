import { map } from "../vue/initMap.js";
import { getMobilitesAutourParking } from "./searchMobilities.js";
import { parkingList } from "../modele/loadParkings.js";

let stopsLayer = null;

export function initMobilities() {
  //Création d’un groupe de layers pour pouvoir ajouter facilement tous les arrêts
  stopsLayer = L.layerGroup().addTo(map);

  function clearStops() {
    stopsLayer.clearLayers();
  }

  window.showMobilites = async (parkingId) => {
    //On nettoie d’abord les anciens arrêts affichés avant de mettre les nouveaux
    clearStops();

    //Recherche du parking correspondant à l’ID cliqué
    const parking = parkingList.find((p) => p.id === parkingId);
    if (!parking) return;

    const stops = await getMobilitesAutourParking(
      { lat: parking.lat, lon: parking.lon },
      500
    );

    if (stops.length === 0) return;

    //Pour chaque arrêt, on affiche un cercle sur la carte
    stops.forEach((stop) => {
      L.circleMarker([stop.lat, stop.lon], {
        radius: 6,
        color: "blue",
        fillOpacity: 0.9,
      })

        // Popup avec nom de l’arrêt + distance depuis le parking
        .bindPopup(`${stop.name}<br>${Math.round(stop.distance)} m`)
        .addTo(stopsLayer);
    });
  };

  //Fonction en globale pour pouvoir y accéder avec le bouton
  window.clearStops = clearStops;
}
