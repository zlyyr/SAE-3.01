export async function loadStops() {
  const response = await fetch("../modele/getStops.php");
  if (!response.ok) {
    throw new Error("Erreur chargement arrêts");
  }
  return await response.json();
}
