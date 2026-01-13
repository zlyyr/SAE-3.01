<?php
require_once "../vue/data/connexion.class.php";

header('Content-Type: application/json; charset=utf-8');

try {
    $conn = new Connexion();

    // ⚠️ adapte le nom de la table et des colonnes si besoin
    $stops = $conn->execSQL("
        SELECT
            id,
            stop_id,
            stop_name AS name,
            lat,
            lon,
        FROM MobilityService
    ");

    echo json_encode($stops);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
