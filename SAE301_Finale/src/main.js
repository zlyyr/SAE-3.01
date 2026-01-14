import { initMap } from "./vue/initMap.js";
import { initGeolocation } from "./modele/geoLoc.js";
import { loadParkings } from "./modele/loadParkings.js";
import { loadLondonParkings } from "./modele/loadParkingsLondon.js";
import { initSearch } from "./controleur/search.js";
import { initNearest } from "./controleur/searchNearest.js";
import { initMobilities } from "./controleur/initMobilities.js";

const city = globalThis.userCity;

// Initialisation
initMap();
initGeolocation(city);
loadParkings();
loadLondonParkings();
initSearch();
initNearest();
initMobilities();
