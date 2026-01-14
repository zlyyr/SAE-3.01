<?php
session_start();
require_once 'data/connexion.class.php'; // On inclut la connexion

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';

  $conn = new Connexion();

  $res = $conn->execSQL(
    "SELECT * FROM Users WHERE email = ?",
    [$email]
  );

  $user = $res[0] ?? null;

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['email'] = $user['email'];
    $_SESSION['user_id'] = $user['ID_User']; // Utile pour les futures requêtes
    $_SESSION['city'] = $user['city'];
    header("Location: index.php");
    exit;
  } else {
    $error = "Email ou mot de passe incorrect.";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="./style/login_register.css" />
</head>

<body>
  <div class="overlay">
    <div class="form-container">
      <div class="title-container">
        <img src="./images/voiture.png" alt="Voiture" class="icon-car" />
        <span class="arrow">→</span>
        <img src="./images/parking.jpg" alt="Parking" class="icon-parking" />
      </div>

      <h1 class="spark-title">Sparking</h1>
      <h2>Connexion</h2>

      <div class="lang-selector">
        <select id="lang-select">
          <option value="fr">Français</option>
          <option value="en">English</option>
        </select>
      </div>

      <?php if (isset($error))
        echo "<p style='color:red'>$error</p>"; ?>
      <form method="POST">
        <input type="text" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
      </form>
      <a href="register.php">Créer un compte</a>
    </div>
  </div>

  <script>
    const translations = {
      fr: {
        h2: "Connexion",
        emailPlaceholder: "Email",
        passwordPlaceholder: "Mot de passe",
        button: "Se connecter",
        link: "Créer un compte",
        error: "Email ou mot de passe incorrect."
      },
      en: {
        h2: "Login",
        emailPlaceholder: "Email",
        passwordPlaceholder: "Password",
        button: "Log in",
        link: "Create an account",
        error: "Incorrect email or password."
      }
    };

    const setLanguage = (lang) => {
      document.documentElement.lang = lang;
      document.querySelector('h2').textContent = translations[lang].h2;
      document.querySelector('input[name="email"]').placeholder = translations[lang].emailPlaceholder;
      document.querySelector('input[name="password"]').placeholder = translations[lang].passwordPlaceholder;
      document.querySelector('button').textContent = translations[lang].button;
      document.querySelector('a').textContent = translations[lang].link;
      localStorage.setItem('lang', lang);
    };

    document.getElementById('lang-select').addEventListener('change', (e) => {
      setLanguage(e.target.value);
    });

    // Load saved language
    const savedLang = localStorage.getItem('lang') || 'fr';
    document.getElementById('lang-select').value = savedLang;
    setLanguage(savedLang);
  </script>

</body>

</html>