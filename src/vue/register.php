<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $city = $_POST['city'] ?? 'metz';

    $usersFile = 'data/users.json';
    $users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];

    // Vérifier si username existe déjà
    foreach ($users as $u) {
        if ($u['username'] === $username) {
            $error = "Nom d'utilisateur déjà utilisé.";
            break;
        }
    }

    if (!isset($error)) {
        // Calculer le nouvel ID
        $newId = count($users) > 0 ? max(array_keys($users)) + 1 : 1;

        $users[$newId] = [
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'city' => $city
        ];

        file_put_contents($usersFile, json_encode($users, JSON_PRETTY_PRINT));

        // Redirection vers login
        header("Location: login.php");
        exit;
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
    <h1 class="spark-title">Sparking</h1>
    <h2>Inscription</h2>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
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
