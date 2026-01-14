import { map } from "../vue/initMap.js";
import { goToParking } from "./routage.js";

export function initSearch() {
  const input = document.getElementById("parkingName");
  const button = document.getElementById("goButton");

  //Rajout d'appuyer sur "entree"
  input.addEventListener("keydown", (e) => {
    if (e.key === "Enter") button.click();
  });
  //Lorsqu'on clique sur le bouton "Aller"
  button.addEventListener("click", () => {
    const search = input.value.toLowerCase();

    const markers = map._layers;
    let found = null;

    //On parcourt les noms des parkings
    Object.values(markers).forEach((layer) => {
      //Quand on trouve le bon, on le stock dans found
      if (
        layer._popup &&
        layer._popup._content.toLowerCase().includes(search)
      ) {
        found = layer;
      }
    });

    //On lance le trajet vers le parking trouvé
    if (found) {
      const latLng = found.getLatLng();
      goToParking(latLng.lat, latLng.lng);
    } else {
      alert("Parking non trouvé. Le nom doit etre exacte.");
    }
  });
}
