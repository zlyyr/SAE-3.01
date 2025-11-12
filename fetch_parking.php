<?php
// fetch_parkings.php
header('Content-Type: application/json');

$overpass_endpoint = "https://overpass-api.de/api/interpreter";

$query = <<<Q
[out:json][timeout:25];
area["name"="Metz"]["boundary"="administrative"]->.searchArea;
(
  node["amenity"="parking"](area.searchArea);
  way["amenity"="parking"](area.searchArea);
  relation["amenity"="parking"](area.searchArea);
);
out center;
Q;

// Prépare les options POST
$options = [
    "http" => [
        "header"  => "Content-type: application/x-www-form-urlencoded\r\nUser-Agent: SparkingApp/1.0\r\n",
        "method"  => "POST",
        "content" => http_build_query(['data' => $query]),
        "timeout" => 30
    ]
];
$context  = stream_context_create($options);

// Appel à Overpass
$response = file_get_contents($overpass_endpoint, false, $context);

if ($response === false) {
    http_response_code(500);
    echo json_encode(["error" => "Impossible de contacter Overpass API"]);
    exit;
}

$data = json_decode($response, true);
$parkings = [];

foreach ($data['elements'] as $el) {
    $name = $el['tags']['name'] ?? 'Parking (sans nom)';
    if (isset($el['lat']) && isset($el['lon'])) {
        $lat = $el['lat'];
        $lon = $el['lon'];
    } elseif (isset($el['center']['lat']) && isset($el['center']['lon'])) {
        $lat = $el['center']['lat'];
        $lon = $el['center']['lon'];
    } else {
        continue;
    }

    $parkings[] = [
        'id' => $el['id'],
        'name' => $name,
        'lat' => (float)$lat,
        'lon' => (float)$lon
    ];
}

// Sauvegarde en JSON pour index.php
file_put_contents(__DIR__.'/parkings.json', json_encode($parkings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Retour JSON simple
echo json_encode(["count"=>count($parkings)]);

