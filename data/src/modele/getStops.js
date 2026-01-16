// getStops.js
export async function getStops() {
  try {
    // Load DB data from sql.json
    const response = await fetch('../data/sql.json');
    const dbData = await response.json();

    // Find MobilityService table
    const stopsTable = dbData.find(item => item.type === 'table' && item.name === 'MobilityService');
    const stops = stopsTable.data.map(s => ({
      id: parseInt(s.id),
      stop_id: s.stop_id,
      name: s.stop_name,
      lat: parseFloat(s.lat),
      lon: parseFloat(s.lon)
    }));

    return stops;
  } catch (e) {
    console.error(e);
    return [];
  }
}