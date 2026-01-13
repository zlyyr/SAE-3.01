<?php
session_start();
require_once 'data/connexion.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $city = $_POST['city'] ?? 'metz';
  $first_name = $_POST['first_name'] ?? ''; // Ajout des nouveaux champs
  $last_name = $_POST['last_name'] ?? '';

  // Vérifier si l'utilisateur existe déjà
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM Users WHERE username = ?");
  $stmt->execute([$username]);

  if ($stmt->fetchColumn() > 0) {
    $error = "Nom d'utilisateur déjà utilisé.";
  } else {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO Users (username, password, city, first_name, last_name) VALUES (?, ?, ?, ?, ?)");

    if ($stmt->execute([$username, $hashedPassword, $city, $first_name, $last_name])) {
      header("Location: login.php");
      exit;
    } else {
      $error = "Une erreur est survenue lors de l'inscription.";
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
      <?php if (isset($error))
        echo "<p style='color:red'>$error</p>"; ?>
      <form method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
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
</body>

</html>