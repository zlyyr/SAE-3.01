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

            <div class="lang-selector">
                <label for="lang-select">Langue / Language:</label>
                <select id="lang-select">
                    <option value="fr">Français</option>
                    <option value="en">English</option>
                </select>
            </div>

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

    <script>
        const translations = {
            fr: {
                title: "Aide — Sparking",
                h1: "Sparking",
                h2: "❓ Aide & Support",
                guidance: "<strong>🧭 Guidage :</strong><br>Cliquez sur un parking puis sur <b>\"M'y amener\"</b> pour lancer l’itinéraire.",
                filters: "<strong>🎯 Filtres :</strong><br>Utilisez le menu en bas à droite pour afficher uniquement les parkings adaptés à votre véhicule.",
                stops: "<strong>📍 Arrêts de mobilité :</strong><br>Dans un parking, cliquez sur <b>\"Arrêts à proximité\"</b> pour voir les arrets de bus les plus proches du parking sélectionné.",
                profile: "<strong>🕵️​ Changement de vos infos :</strong><br>Utilser le menu et aller dans <b>\"Profil\"</b>, puis <b>\"Modifier vos informations\"</b> afin de changer vos informations de profil comme le nom ou prénom",
                support: "<strong>📧 Support :</strong><br>Pour toutes autres questions, veuillez nous contacter sur <b>contact@iut-metz.fr</b>",
                back: "Retour à la carte",
                langLabel: "Langue / Language:"
            },
            en: {
                title: "Help — Sparking",
                h1: "Sparking",
                h2: "❓ Help & Support",
                guidance: "<strong>🧭 Guidance:</strong><br>Click on a parking then on <b>\"Take me there\"</b> to start the route.",
                filters: "<strong>🎯 Filters:</strong><br>Use the menu at the bottom right to display only the parkings suitable for your vehicle.",
                stops: "<strong>📍 Mobility stops:</strong><br>In a parking, click on <b>\"Nearby stops\"</b> to see the bus stops closest to the selected parking.",
                profile: "<strong>🕵️​ Changing your info:</strong><br>Use the menu and go to <b>\"Profile\"</b>, then <b>\"Modify your information\"</b> to change your profile information like name or first name",
                support: "<strong>📧 Support:</strong><br>For any other questions, please contact us at <b>contact@iut-metz.fr</b>",
                back: "Back to map",
                langLabel: "Langue / Language:"
            }
        };

        const langSelect = document.getElementById('lang-select');
        const langLabel = document.querySelector('.lang-selector label');

        const setLanguage = (lang) => {
            document.title = translations[lang].title;
            document.documentElement.lang = lang;
            document.querySelector('h1').textContent = translations[lang].h1;
            document.querySelector('h2').textContent = translations[lang].h2;
            langLabel.textContent = translations[lang].langLabel;
            const ps = document.querySelectorAll('.help-content p');
            ps[0].innerHTML = translations[lang].guidance;
            ps[1].innerHTML = translations[lang].filters;
            ps[2].innerHTML = translations[lang].stops;
            ps[3].innerHTML = translations[lang].profile;
            ps[4].innerHTML = translations[lang].support;
            document.querySelector('button').textContent = translations[lang].back;
            localStorage.setItem('lang', lang);
        };

        langSelect.addEventListener('change', (e) => {
            setLanguage(e.target.value);
        });

        // Load saved language or default to fr
        const savedLang = localStorage.getItem('lang') || 'fr';
        langSelect.value = savedLang;
        setLanguage(savedLang);
    </script>

</body>

</html>