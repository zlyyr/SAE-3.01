import { parkingList } from "../modele/loadParkings.js";
import { londonParkingList } from "../modele/loadParkingsLondon.js";
import { userMarker } from "../modele/geoLoc.js";
import { map } from "../vue/initMap.js";
import { goToParking } from "./routage.js";
import { distance } from "../modele/utils.js";

export function initNearest() {
  //On initialise le bouton "Le plus proche"
  const btn = document.getElementById("nearestBtn");

  btn.addEventListener("click", () => {
    if (!userMarker) {
      alert("Position utilisateur indisponible !");
      return;
    }

    const userPos = userMarker.getLatLng();

    const allParkings = [...parkingList, ...londonParkingList];

    let closest = null;
    let bestDistance = Infinity;
    //On calcule la distance des parkings par rapport à l'utilisateur
    allParkings.forEach((p) => {
      const d = distance(userPos.lat, userPos.lng, p.lat, p.lon);

      //Si la distance est plus courte, on la stocke dans bestDistance
      if (d < bestDistance) {
        bestDistance = d;
        closest = p;
      }
    });

    if (!closest) {
      alert("Aucun parking chargé !");
      return;
    }

    //Centre la carte sur le parking proche
    map.setView([closest.lat, closest.lon], 17);

    //Lance le guidage
    goToParking(closest.lat, closest.lon);
  });
}
