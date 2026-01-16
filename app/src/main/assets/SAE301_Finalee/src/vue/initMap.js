export let map = null;

export function initMap() {
  map = L.map("map").setView([49.1193, 6.1757], 14);

  //On initialise la map avec Leaflet
  L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "© OpenStreetMap",
  }).addTo(map);
}
