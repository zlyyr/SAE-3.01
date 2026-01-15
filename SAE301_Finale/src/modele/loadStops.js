export async function loadStops() {
  //On charge les services de mobilité ou on envoie une erreur
  const response = await fetch("../modele/getStops.php");
  if (!response.ok) {
    throw new Error("Erreur chargement arrêts");
  }
  return await response.json();
}
