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

      <div class="profile-info">
        <div class="avatar">👤</div>

        <p><strong>Prénom :</strong> <?= htmlspecialchars($user['first_name']) ?></p>
        <p><strong>Nom :</strong> <?= htmlspecialchars($user['last_name']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Ville :</strong> <?= htmlspecialchars($user['city']) ?></p>
      </div>

      <button onclick="location.href='index.php'">Retour à la carte</button>

    </div>
  </div>
</body>

</html>