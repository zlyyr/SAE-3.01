// getParking.js
export async function getParkings() {
  try {
    // Load DB data from sql.json
    const response = await fetch('../data/sql.json');
    const dbData = await response.json();

    // Find ParkingMetz table
    const parkingTable = dbData.find(item => item.type === 'table' && item.name === 'ParkingMetz');
    const parkings = parkingTable.data;

    // Fetch API data
    const apiUrl = "https://maps.eurometropolemetz.eu/public/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=public:pub_tsp_sta&srsName=EPSG:4326&outputFormat=application/json&cql_filter=id%20is%20not%20null";
    const apiResponse = await fetch(apiUrl);
    const apiData = await apiResponse.json();

    // Index API
    const byId = {};
    const byName = {};

    apiData.features.forEach(f => {
      const p = f.properties;
      if (p.id) {
        byId[p.id] = p;
      }
      if (p.lib) {
        byName[p.lib] = p;
      }
    });

    // Merge
    const out = [];

    parkings.forEach(p => {
      // Convert fields
      const parking = {
        id: p.id,
        id_api: p.id_api,
        name: p.nom,
        adresse: p.adresse,
        gratuit: parseInt(p.gratuit),
        nb_places: parseInt(p.nb_places),
        nb_pmr: parseInt(p.nb_pmr || 0),
        nb_velo: parseInt(p.nb_velo || 0),
        nb_voitures_electriques: parseInt(p.nb_voitures_electriques || 0),
        lat: parseFloat(p.ylat),
        lon: parseFloat(p.xlong),
        tarif_1h: parseFloat(p.tarif_1h),
        tarif_2h: parseFloat(p.tarif_2h),
        tarif_24h: parseFloat(p.tarif_24h),
        url: p.url,
        info: p.info
      };

      // Merge with API
      const api = parking.id_api ? (byId[parking.id_api] || null) : null;
      if (api) {
        parking.place_libre = api.place_libre || null;
        parking.place_total_rt = api.place_total || null;
        parking.place_update = api.place_update || null;
        parking.place_variation = api.place_variation || null;
      }

      out.push(parking);
    });

    return out;
  } catch (e) {
    console.error(e);
    return [];
  }
}