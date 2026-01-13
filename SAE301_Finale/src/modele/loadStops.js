export async function loadStops() {
    const response = await fetch("vue/data/stops.json");
    return await response.json();
}
