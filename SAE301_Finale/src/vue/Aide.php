<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Aide — Sparking</title>
    <link rel="stylesheet" href="./style/aide.css">
</head>

<body>

    <div class="overlay">
        <div class="form-container">

            <h1 class="spark-title">Sparking</h1>
            <h2>❓ Aide & Support</h2>

            <div class="help-content">
                <p><strong>🧭 Guidage :</strong><br>
                    Cliquez sur un parking puis sur <b>"M'y amener"</b> pour lancer l’itinéraire.</p>

                <p><strong>🎯 Filtres :</strong><br>
                    Utilisez le menu en bas à droite pour afficher uniquement les parkings adaptés à votre
                    véhicule.</p>

                <p><strong>📍 Arrêts de mobilité :</strong><br>
                    Dans un parking, cliquez sur <b>"Arrêts à proximité"</b> pour voir les arrets de bus les plus
                    proches du parking sélectionné.</p>

                <p><strong>🕵️​ Changement de vos infos :</strong><br>
                    Utilser le menu et aller dans <b>"Profil"</b>, puis <b>"Modifier vos informations"</b> afin de
                    changer vos informations de profil comme le nom ou prénom</p>

                <p><strong>📧 Support :</strong><br>
                    Pour toutes autres questions, veuillez nous contacter sur <b>contact@iut-metz.fr</b></p>
            </div>

            <button onclick="location.href='index.php'">Retour à la carte</button>

        </div>
    </div>

</body>

</html>