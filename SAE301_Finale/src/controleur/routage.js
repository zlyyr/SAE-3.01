import { map } from "../vue/initMap.js";
import { userMarker } from "../modele/geoLoc.js";

let routeControl = null;
let currentTarget = null;

export function goToParking(lat, lon) {
  //On vérifie qu'on a la position de l'utilisateur
  if (!userMarker) {
    alert("Position utilisateur non disponible.");
    return;
  }

  currentTarget = { lat, lon };

  //On stocke les coordonnées le l'utilisateur
  const { lat: userLat, lng: userLon } = userMarker.getLatLng();

  if (routeControl) map.removeControl(routeControl);

  routeControl = L.Routing.control({
    waypoints: [
      L.latLng(userLat, userLon),
      L.latLng(lat, lon)
    ],
    //Pour enlever les marqueurs bleus de Leaflet
    createMarker: () => null,
    routeWhileDragging: false,
    //Options de la route
    lineOptions: {
      addWaypoints: false,
      styles: [{ color: "red", weight: 8, opacity: 1 }]
    },
    router: L.Routing.osrmv1({
      serviceUrl: "https://router.project-osrm.org/route/v1"
    }),
    show: false,
    collapsible: true
  }).addTo(map);

  //On zoom sur le départ et l'arrivée
  map.fitBounds([
    [userLat, userLon],
    [lat, lon]
  ]);
}

//On recalcule la position si l'utilisateur bouge
export function recalculateRoute() {
  if (!currentTarget || !userMarker) return;
  goToParking(currentTarget.lat, currentTarget.lon);
}

//On rend la fonction globale
globalThis.goToParking = goToParking;

