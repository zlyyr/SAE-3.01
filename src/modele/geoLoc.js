import { map } from "../vue/initMap.js";
export let userMarker = null;

//Permet de créer un marker même sans les vraies positions
function createFakePosition(lat, lon) {
  return {
    coords: { latitude: lat, longitude: lon }
  };
}

export function updateUserPosition(position) {
  const lat = position.coords.latitude;
  const lon = position.coords.longitude;

  //On met un marker personnalisé pour l'utilisateur
  if (!userMarker) {
    userMarker = L.marker([lat, lon], {
      icon: L.icon({
        iconUrl: "https://cdn-icons-png.flaticon.com/512/684/684908.png",
        iconSize: [32, 32],
      }),
      //On rajoute un popup quand on clique sur son marqueur
    }).addTo(map).bindPopup("Vous êtes ici");

    map.setView([lat, lon], 15);
  } else {
    userMarker.setLatLng([lat, lon]);
  }
  if (globalThis.currentTarget) {
    recalculateRoute();
  }

}
//On initialise une première fois la position de l'utilisateur
export function initGeolocation(city) {
  //Si y a pas de localisation, on met une fausse à l'ile du saulcy
  if (!navigator.geolocation) {
    console.log("Géolocalisation non supportée, utilisation position fixe.");
    updateUserPosition(createFakePosition(51.5074, -0.1278));
    return;
  }
  //On récupère la position de l'utilisateur
  navigator.geolocation.getCurrentPosition(
    (pos) => updateUserPosition(pos),
    () => {
      //Si l'utilisateur refuse l'accès ou erreur, on utilise la fausse position
      console.log("Accès refusé, position fixe.");
      if (city === "london") {
        updateUserPosition(createFakePosition(51.5074, -0.1278));
      }
      else {
        updateUserPosition(createFakePosition(49.1193, 6.1757));
      }
      
    }
  );
}

