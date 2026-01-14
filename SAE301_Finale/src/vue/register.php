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
</body>

</html>