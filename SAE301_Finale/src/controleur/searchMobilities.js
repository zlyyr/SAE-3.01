import { loadStops } from "../modele/loadStops.js";
import { distance } from "../modele/utils.js";

//Fonction qui renvoie les arrêts proches d’un parking dans un rayon donné
export async function getMobilitesAutourParking(parking, rayon = 500) {

  //Chargement de tous les arrêts depuis la BDD
  const stops = await loadStops();

  return stops

      //On calcule la distance entre chaque les arrêts et le parking
    .map((stop) => {
      const slat = Number(stop.lat);
      const slon = Number(stop.lon);

      const d = distance(Number(parking.lat), Number(parking.lon), slat, slon);

      return {
        ...stop,
        distance: d,
      };
    })
    .filter((stop) => Number.isFinite(stop.distance) && stop.distance <= rayon)
    //On trie du plus proche au plus loin
    .sort((a, b) => a.distance - b.distance)
    //On garde seulement les 5 plus proches
    .slice(0, 5);
}
