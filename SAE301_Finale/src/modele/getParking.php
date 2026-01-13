<?php
require_once "../vue/data/connexion.class.php";

header('Content-Type: application/json; charset=utf-8');

try {

    // Connexion BDD
    $conn = new Connexion();

    // 1) BDD
    $parkings = $conn->execSQL("
        SELECT id, id_api, nom AS name, adresse, gratuit, nb_places, nb_pmr, nb_velo,
               nb_voitures_electriques, Ylat AS lat, Xlong AS lon,
               tarif_1h, tarif_2h, tarif_24h, url, info
        FROM ParkingMetz
    ");

    // 2) API temps réel Metz
    $apiUrl = "https://maps.eurometropolemetz.eu/public/ows?service=WFS&version=1.0.0&request=GetFeature&typeName=public:pub_tsp_sta&srsName=EPSG:4326&outputFormat=application/json&cql_filter=id%20is%20not%20null";
    $apiJson = file_get_contents($apiUrl);
    $apiData = json_decode($apiJson, true);

    // Index API
    $byId = [];
    $byName = [];

    foreach ($apiData["features"] as $f) {
        $p = $f["properties"];
        if (!empty($p["id"])) {
            $byId[$p["id"]] = $p;
        }
        if (!empty($p["lib"])) {
            $byName[$p["lib"]] = $p;
        }
    }

    // 3) Fusion
    $out = [];

    foreach ($parkings as $p) {

        foreach (["lat", "lon", "tarif_1h", "tarif_2h", "tarif_24h"] as $k)
            $p[$k] = (float) $p[$k];

        foreach (["nb_places", "nb_pmr", "nb_velo", "nb_voitures_electriques"] as $k)
            $p[$k] = (int) $p[$k];

        $api = !empty($p["id_api"]) ? ($byId[$p["id_api"]] ?? null) : null;

        $p["place_libre"] = $api["place_libre"] ?? null;
        $p["place_total_rt"] = $api["place_total"] ?? null;
        $p["place_update"] = $api["place_update"] ?? null;
        $p["place_variation"] = $api["place_variation"] ?? null;

        $out[] = $p;
    }

    echo json_encode($out);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
