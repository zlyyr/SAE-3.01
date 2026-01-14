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
    <button id="stopBtn" style="display:none;">Annuler le trajet</button>
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

      <button id="lang-toggle">🌐 FR</button>

      <a href="logout.php" class="logout">🚪 Se déconnecter</a>

    </div>
  </nav>

  <script>
    const menu = document.getElementById("menu");
    const btn = document.getElementById("menu-btn");

    btn.addEventListener("click", () => {
      menu.classList.toggle("open");
    });

    const translations = {
      fr: {
        searchPlaceholder: "Rechercher un parking…",
        goButton: "Aller",
        nearestBtn: "Le plus proche",
        cancelTrip: "Annuler le trajet",
        profile: "👤 Profil",
        help: "❓ Aide / Support",
        filters: "Filtres",
        velo: "🚲 Places Vélos",
        pmr: "♿ Places Handicapées (PMR)",
        electric: "⚡ Bornes Électriques",
        free: "💷 Gratuits",
        logout: "🚪 Se déconnecter",
        footer: "© 2025 Sparking — Meme une fusée pourrait se garer !",
        langToggle: "🌐 FR"
      },
      en: {
        searchPlaceholder: "Search for a parking…",
        goButton: "Go",
        nearestBtn: "Nearest",
        cancelTrip: "Cancel trip",
        profile: "👤 Profile",
        help: "❓ Help / Support",
        filters: "Filters",
        velo: "🚲 Bike spots",
        pmr: "♿ Disabled parking (PMR)",
        electric: "⚡ Electric chargers",
        free: "💷 Free",
        logout: "🚪 Log out",
        footer: "© 2025 Sparking — Even a rocket could park here!",
        langToggle: "🌐 EN"
      }
    };

    const setLanguage = (lang) => {
      document.documentElement.lang = lang;
      const parkingName = document.getElementById('parkingName');
      if (parkingName) parkingName.placeholder = translations[lang].searchPlaceholder;
      const goButton = document.getElementById('goButton');
      if (goButton) goButton.textContent = translations[lang].goButton;
      const nearestBtn = document.getElementById('nearestBtn');
      if (nearestBtn) nearestBtn.textContent = translations[lang].nearestBtn;
      const stopBtn = document.getElementById('stopBtn');
      if (stopBtn) stopBtn.textContent = translations[lang].cancelTrip;
      const profilLink = document.querySelector('a[href="Profil.php"]');
      if (profilLink) profilLink.textContent = translations[lang].profile;
      const aideLink = document.querySelector('a[href="Aide.php"]');
      if (aideLink) aideLink.textContent = translations[lang].help;
      const filtersH2 = document.querySelector('.form-container h2');
      if (filtersH2) filtersH2.textContent = translations[lang].filters;
      const filterPs = document.querySelectorAll('.form-container p');
      if (filterPs[0] && filterPs[0].lastChild) filterPs[0].lastChild.textContent = translations[lang].velo;
      if (filterPs[1] && filterPs[1].lastChild) filterPs[1].lastChild.textContent = translations[lang].pmr;
      if (filterPs[2] && filterPs[2].lastChild) filterPs[2].lastChild.textContent = translations[lang].electric;
      if (filterPs[3] && filterPs[3].lastChild) filterPs[3].lastChild.textContent = translations[lang].free;
      const logout = document.querySelector('.logout');
      if (logout) logout.textContent = translations[lang].logout;
      const footer = document.querySelector('footer');
      if (footer) footer.textContent = translations[lang].footer;
      const langToggle = document.getElementById('lang-toggle');
      if (langToggle) langToggle.textContent = translations[lang].langToggle;
      localStorage.setItem('lang', lang);
    };

    const k = "filters_<?php echo $_SESSION['email'] ?? 'guest'; ?>";

    const cb = {
      v: document.getElementById('v'),
      pmr: document.getElementById('pmr'),
      e: document.getElementById('e'),
      p: document.getElementById('p')
    };

    const d = JSON.parse(localStorage.getItem(k)) || { v: false, pmr: <?= $user_pmr ? 'true' : 'false' ?>, e: <?= $user_motorization === 'Electric' ? 'true' : 'false' ?>, p: false };
    cb.v.checked = d.v;
    cb.pmr.checked = d.pmr;
    cb.e.checked = d.e;
    cb.p.checked = d.p;

    // Trigger initial filtering
    cb.v.dispatchEvent(new Event('change'));
    cb.pmr.dispatchEvent(new Event('change'));
    cb.e.dispatchEvent(new Event('change'));
    cb.p.dispatchEvent(new Event('change'));

    // Language toggle
    const langToggle = document.getElementById('lang-toggle');
    langToggle.addEventListener('click', () => {
      const currentLang = localStorage.getItem('lang') || 'fr';
      const newLang = currentLang === 'fr' ? 'en' : 'fr';
      setLanguage(newLang);
    });

    // Load saved language or default to fr
    const savedLang = localStorage.getItem('lang') || 'fr';
    setLanguage(savedLang);
  </script>

  <div id="map"></div>

  <footer>© 2025 Sparking — Meme une fusée pourrait se garer !</footer>


  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
  <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

  <script type="module" src="../main.js"></script>
</body>

</html>