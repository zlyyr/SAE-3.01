<?php
$host = 'devbdd.iutmetz.univ-lorraine.fr';
$dbname = 'e98735u_SAE301';
$port = '3306';
$user = 'e98735u_appli';
$pass = '32402754';
try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>