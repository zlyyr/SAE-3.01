<?php
session_start();
if (!isset($_SESSION["user"])) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <title>Sparking 🚗</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />

  <link rel="stylesheet" href="./style/index.css" />

</head>

<body>

  <header>Sparking</header>

  <div id="searchBar">
    <input type="text" id="parkingName" placeholder="Rechercher un parking…" />
    <button id="goButton">Aller</button>
    <button id="nearestBtn">Le plus proche</button>
  </div>

  <nav class="Lt" id="menu">
    <img src="./images/menu.png" alt="Menu" class="icon-menu" id="menu-btn">

    <div class="nav-content">
      <a href="Inscription.html">
        <div class="nav-content">
          <a href="Profil.html">👤 Profil</a>
          <a href="Parametres.html">⚙️ Paramètres</a>
          <a href="Filtres.html">Filtres</a>
          <a href="Aide.html">❓ Aide / Support</a>

          <hr>

          <a href="Deconnexion.html" class="logout">🚪 Se déconnecter</a>
        </div>
      </a>
    </div>
  </nav>
  <script>
    const menu = document.getElementById("menu");
    const btn = document.getElementById("menu-btn");

    btn.addEventListener("click", () => {
      menu.classList.toggle("open");
    });
  </script>


  <div id="map"></div>

  <footer>© 2025 Sparking — L’étincelle qui te guide vers ta place</footer>

  <script type="module">
    globalThis.userCity = <?= json_encode($_SESSION['city']) ?>;
  </script>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

  <script type="module" src="../main.js"></script>
</body>

</html>