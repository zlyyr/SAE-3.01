export async function loadStops() {
    const response = await fetch("../modele/getStops.php");
    return await response.json();
  }
  