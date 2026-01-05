import { map } from "../vue/initMap.js";

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
      if (layer._popup && layer._popup._content.toLowerCase().includes(search)) {
        found = layer;
      }
    });

    //On zoom sur le parking et on affiche son popup
    if (found) {
      map.setView(found.getLatLng(), 17);
      found.openPopup();
    } else {
      alert("Parking non trouvé. Le nom doit etre exacte.");
    }
  });
}
