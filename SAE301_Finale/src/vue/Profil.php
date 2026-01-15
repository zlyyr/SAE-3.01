<?php
session_start();
require_once 'data/connexion.class.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$conn = new Connexion();

//Récupère les informations de l'utilisateur connecté
$res = $conn->execSQL(
  "SELECT first_name, last_name, email, city FROM Users WHERE ID_User = ?",
  [$_SESSION['user_id']]
);

$user = $res[0] ?? null;

if (!$user) {
  die("Utilisateur introuvable.");
}

//Si le formulaire de modification est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';

  //Validation des champs
  if (empty($first_name) || empty($last_name)) {
    $error = "Veuillez remplir tous les champs.";
  } else if (!preg_match("/^[a-zA-ZÀ-ÿ '-]+$/u", $first_name)) {
    $error = "Le prénom ne peut contenir que des lettres.";
  } else if (!preg_match("/^[a-zA-ZÀ-ÿ '-]+$/u", $last_name)) {
    $error = "Le nom ne peut contenir que des lettres.";
  } else {
    $conn->execSQL(
      "UPDATE Users SET first_name = ?, last_name = ? WHERE ID_User = ?",
      [$first_name, $last_name, $_SESSION['user_id']]
    );
    $success = "Informations mises à jour.";
    //Mettre à jour la variable $user pour afficher les nouvelles infos
    $user['first_name'] = $first_name;
    $user['last_name'] = $last_name;
  }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Mon Profil — Sparking</title>
  <link rel="stylesheet" href="./style/profil.css">
</head>

<body>
  <div class="overlay">
    <div class="form-container">

      <h1 class="spark-title">Sparking</h1>
      <h2>Mon Profil</h2>

      <div class="lang-selector">
        <select id="lang-select">
          <option value="fr">Français</option>
          <option value="en">English</option>
        </select>
      </div>

      <?php if (isset($error))
        echo "<p style='color:red'>$error</p>"; ?>
      <?php if (isset($success))
        echo "<p style='color:green'>$success</p>"; ?>

      <form method="POST" id="editForm">
        <div class="profile-info">
          <div class="avatar">👤</div>

          <p><strong id="firstNameLabel">Prénom :</strong>
            <span class="view"><?= htmlspecialchars($user['first_name']) ?></span>
            <input class="edit" type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>"
              style="display:none;">
          </p>

          <p><strong id="lastNameLabel">Nom :</strong>
            <span class="view"><?= htmlspecialchars($user['last_name']) ?></span>
            <input class="edit" type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>"
              style="display:none;">
          </p>

          <p><strong id="emailLabel">Email :</strong> <?= htmlspecialchars($user['email']) ?></p>

          <p><strong id="cityLabel">Ville :</strong> <?= htmlspecialchars($user['city']) ?></p>
        </div>

        <button type="button" id="editBtn">Modifier vos informations</button>
        <button type="submit" id="saveBtn" style="display:none;">Enregistrer</button>
        <button type="button" id="backBtn" onclick="location.href='index.php'">Retour à la carte</button>
      </form>
    </div>
  </div>

  <script>
    const translations = {
      fr: {
        h2: "Mon Profil",
        firstName: "Prénom",
        lastName: "Nom",
        email: "Email",
        city: "Ville",
        editBtn: "Modifier vos informations",
        saveBtn: "Enregistrer",
        backBtn: "Retour à la carte"
      },
      en: {
        h2: "My Profile",
        firstName: "First name",
        lastName: "Last name",
        email: "Email",
        city: "City",
        editBtn: "Modify your information",
        saveBtn: "Save",
        backBtn: "Back to map"
      }
    };

    //Appliquer la langue sélectionnée
    const setLanguage = (lang) => {
      document.documentElement.lang = lang;
      document.querySelector('h2').textContent = translations[lang].h2;
      document.getElementById('firstNameLabel').textContent = translations[lang].firstName + ' :';
      document.getElementById('lastNameLabel').textContent = translations[lang].lastName + ' :';
      document.getElementById('emailLabel').textContent = translations[lang].email + ' :';
      document.getElementById('cityLabel').textContent = translations[lang].city + ' :';
      document.getElementById('editBtn').textContent = translations[lang].editBtn;
      document.getElementById('saveBtn').textContent = translations[lang].saveBtn;
      document.getElementById('backBtn').textContent = translations[lang].backBtn;
      localStorage.setItem('lang', lang);
    };

    //Charger la langue sauvegardée ou défaut fr
    document.getElementById('lang-select').addEventListener('change', (e) => {
      setLanguage(e.target.value);
    });

    const savedLang = localStorage.getItem('lang') || 'fr';
    document.getElementById('lang-select').value = savedLang;
    setLanguage(savedLang);

    //Gestion du mode édition
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const views = document.querySelectorAll('.view');
    const edits = document.querySelectorAll('.edit');

    editBtn.addEventListener('click', () => {
      views.forEach(v => v.style.display = 'none');
      edits.forEach(e => e.style.display = 'inline-block');
      editBtn.style.display = 'none';
      saveBtn.style.display = 'inline-block';
    });
  </script>
</body>

</html>