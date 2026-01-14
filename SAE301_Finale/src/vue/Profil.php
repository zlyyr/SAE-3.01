<?php
session_start();
require_once 'data/connexion.class.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$conn = new Connexion();

$res = $conn->execSQL(
  "SELECT first_name, last_name, email, city FROM Users WHERE ID_User = ?",
  [$_SESSION['user_id']]
);

$user = $res[0] ?? null;

if (!$user) {
  die("Utilisateur introuvable.");
}

// Si le formulaire de modification est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first_name = $_POST['first_name'] ?? '';
  $last_name = $_POST['last_name'] ?? '';

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
    // Mettre à jour la variable $user pour afficher les nouvelles infos
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

      <?php if (isset($error))
        echo "<p style='color:red'>$error</p>"; ?>
      <?php if (isset($success))
        echo "<p style='color:green'>$success</p>"; ?>

      <form method="POST" id="editForm">
        <div class="profile-info">
          <div class="avatar">👤</div>

          <p><strong>Prénom :</strong>
            <span class="view"><?= htmlspecialchars($user['first_name']) ?></span>
            <input class="edit" type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>"
              style="display:none;">
          </p>

          <p><strong>Nom :</strong>
            <span class="view"><?= htmlspecialchars($user['last_name']) ?></span>
            <input class="edit" type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>"
              style="display:none;">
          </p>

          <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>

          <p><strong>Ville :</strong> <?= htmlspecialchars($user['city']) ?></p>
        </div>

        <button type="button" id="editBtn">Modifier vos informations</button>
        <button type="submit" id="saveBtn" style="display:none;">Enregistrer</button>
        <button type="button" onclick="location.href='index.php'">Retour à la carte</button>
      </form>
    </div>
  </div>

  <script>
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