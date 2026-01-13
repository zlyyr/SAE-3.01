<?php
session_start();
require_once 'data/connexion.class.php'; // On inclut la connexion

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  $conn = new Connexion();

  $res = $conn->execSQL(
    "SELECT * FROM Users WHERE username = ?",
    [$username]
  );

  $user = $res[0] ?? null;

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $user['username'];
    $_SESSION['user_id'] = $user['ID_User']; // Utile pour les futures requêtes
    $_SESSION['city'] = $user['city'];
    header("Location: index.php");
    exit;
  } else {
    $error = "Nom d'utilisateur ou mot de passe incorrect.";
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
      <?php if (isset($error))
        echo "<p style='color:red'>$error</p>"; ?>
      <form method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
      </form>
      <a href="register.php">Créer un compte</a>
    </div>
  </div>
</body>

</html>