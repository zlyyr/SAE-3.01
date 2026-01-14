<?php
session_start();
if (!isset($_SESSION["email"])) {
  header("Location: login.php");
  exit;
}

require_once 'data/connexion.class.php';
$conn = new Connexion();
$user = $conn->execSQL("SELECT * FROM Users WHERE email = ?", [$_SESSION['email']]);
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
    <button hidden=false id="arretTrajet">Annuler le trajet</button>
  </div>

  <nav class="Lt" id="menu">
    <img src="./images/menu.png" alt="Menu" class="icon-menu" id="menu-btn">

    <div class="nav-content">

      <a href="Profil.php">👤 Profil</a>
      <a href="Aide.php">❓ Aide / Support</a>

      <div class="form-container">
        <h2>Filtres</h2>

        <div style="text-align: left; margin: 20px 0;">
          <p><input type="checkbox" id="v"> 🚲 Places Vélos</p>
          <p><input type="checkbox" id="pmr"> ♿ Places Handicapées (PMR)</p>
          <p><input type="checkbox" id="e"> ⚡ Bornes Électriques</p>
          <p><input type="checkbox" id="p"> 💷 Gratuits</p>
        </div>
      </div>

      <hr>

      <a href="logout.php" class="logout">🚪 Se déconnecter</a>

    </div>
  </nav>

  <script>
    const menu = document.getElementById("menu");
    const btn = document.getElementById("menu-btn");

    btn.addEventListener("click", () => {
      menu.classList.toggle("open");
    });

    const k = "filters_<?php echo $_SESSION['email'] ?? 'guest'; ?>";

    const cb = {
      v: document.getElementById('v'),
      pmr: document.getElementById('pmr'),
      e: document.getElementById('e'),
      p: document.getElementById('p')
    };

    const d = JSON.parse(localStorage.getItem(k)) || { v: false, pmr: false, e: false, p: false };
    cb.v.checked = d.v;
    cb.pmr.checked = d.pmr;
    cb.e.checked = d.e;
    cb.p.checked = d.p;
  </script>

  <div id="map"></div>

  <footer>© 2025 Sparking — Meme une fusée pourrait se garer !</footer>


  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

  <script type="module" src="../main.js"></script>
</body>

</html>