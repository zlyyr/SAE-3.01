<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $usersFile = 'data/users.json';
    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

    $found = false;
    foreach ($users as $id => $u) {
        if ($u['username'] === $username && password_verify($password, $u['password'])) {
            $_SESSION['user'] = $username;
            $_SESSION['city'] = $u['city'];
            $found = true;
            header("Location: index.php");
            exit;
        }
    }

    if (!$found) {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

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
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
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
