<?php
session_start();
require_once 'data/connexion.class.php';

$conn = new Connexion();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  $city = $_POST['city'] ?? 'metz';
  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';

  // Vérifier si l'utilisateur existe déjà
  $existing = $conn->execSQL("SELECT COUNT(*) as count FROM Users WHERE email = ?", [$email]);

  if (strpos($email, '@') === false) {
    $error = "Email au mauvais format.";
  } else if ($existing[0]['count'] > 0) {
    $error = "Email déjà utilisé.";
  } else if (!preg_match("/^[a-zA-ZÀ-ÿ '-]+$/u", $first_name)) {
    $error = "Le prénom ne peut contenir que des lettres.";
  } else if (!preg_match("/^[a-zA-ZÀ-ÿ '-]+$/u", $last_name)) {
    $error = "Le nom ne peut contenir que des lettres.";
  } else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    // INSERT
    try {
      $conn->execSQL("INSERT INTO Users (email, password, city, first_name, last_name) VALUES (?, ?, ?, ?, ?)", [$email, $hashedPassword, $city, $first_name, $last_name]);
      header("Location: register.php");
      exit;
    } catch (Exception $e) {
      $error = "Une erreur est survenue lors de l'inscription : " . $e->getMessage();
    }
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
      <h1 class="spark-title">Sparking</h1>
      <h2>Inscription</h2>

      <div class="lang-selector">
        <select id="lang-select">
          <option value="fr">Français</option>
          <option value="en">English</option>
        </select>
      </div>

      <?php if (isset($error))
        echo "<p style='color:red'>$error</p>"; ?>
      <form method="POST">
        <input type="text" name="first_name" placeholder="Prénom" required value="<?= $first_name ?? '' ?>">
        <input type="text" name="last_name" placeholder="Nom de famille" required value="<?= $last_name ?? '' ?>">
        <input type="text" name="email" placeholder="Email" required value="<?= $email ?? '' ?>">
        <input type="password" name="password" placeholder="Mot de passe" required>
        <select name="city" required>
          <option value="">Choisir la ville</option>
          <option value="metz">Metz</option>
          <option value="london">Londres</option>
        </select>
        <button type="submit">S'inscrire</button>
      </form>
      <a href="login.php">J'ai déjà un compte</a>
    </div>
  </div>

  <script>
    const translations = {
      fr: {
        h2: "Inscription",
        firstName: "Prénom",
        lastName: "Nom de famille",
        email: "Email",
        password: "Mot de passe",
        city: "Choisir la ville",
        metz: "Metz",
        london: "Londres",
        button: "S'inscrire",
        link: "J'ai déjà un compte"
      },
      en: {
        h2: "Registration",
        firstName: "First name",
        lastName: "Last name",
        email: "Email",
        password: "Password",
        city: "Choose city",
        metz: "Metz",
        london: "London",
        button: "Sign up",
        link: "I already have an account"
      }
    };

    const setLanguage = (lang) => {
      document.documentElement.lang = lang;
      document.querySelector('h2').textContent = translations[lang].h2;
      document.querySelector('input[name="first_name"]').placeholder = translations[lang].firstName;
      document.querySelector('input[name="last_name"]').placeholder = translations[lang].lastName;
      document.querySelector('input[name="email"]').placeholder = translations[lang].email;
      document.querySelector('input[name="password"]').placeholder = translations[lang].password;
      const select = document.querySelector('select[name="city"]');
      select.options[0].text = translations[lang].city;
      select.options[1].text = translations[lang].metz;
      select.options[2].text = translations[lang].london;
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