export const translations = {
  fr: {
    searchPlaceholder: "Rechercher un parking…",
    goButton: "Aller",
    nearestBtn: "Le plus proche",
    cancelTrip: "Annuler le trajet",
    profile: "👤 Profil",
    help: "❓ Aide / Support",
    filters: "Filtres",
    velo: "🚲 Places Vélos",
    pmr: "♿ Places Handicapées (PMR)",
    electric: "⚡ Bornes Électriques",
    free: "💷 Gratuits",
    logout: "🚪 Se déconnecter",
    footer: "© 2025 Sparking — Meme une fusée pourrait se garer !",
    langToggle: "🌐 FR",
  },
  en: {
    searchPlaceholder: "Search for a parking…",
    goButton: "Go",
    nearestBtn: "Nearest",
    cancelTrip: "Cancel trip",
    profile: "👤 Profile",
    help: "❓ Help / Support",
    filters: "Filters",
    velo: "🚲 Bike spots",
    pmr: "♿ Disabled parking (PMR)",
    electric: "⚡ Electric chargers",
    free: "💷 Free",
    logout: "🚪 Log out",
    footer: "© 2025 Sparking — Even a rocket could park here!",
    langToggle: "🌐 EN",
  },
};

export function setLanguage(lang) {
  //Met à jour l'attribut lang de la page HTML
  document.documentElement.lang = lang;
  const parkingName = document.getElementById("parkingName");
  if (parkingName)
    parkingName.placeholder = translations[lang].searchPlaceholder;
  const goButton = document.getElementById("goButton");
  if (goButton) goButton.textContent = translations[lang].goButton;
  const nearestBtn = document.getElementById("nearestBtn");
  if (nearestBtn) nearestBtn.textContent = translations[lang].nearestBtn;
  const stopBtn = document.getElementById("stopBtn");
  if (stopBtn) stopBtn.textContent = translations[lang].cancelTrip;
  const profilLink = document.querySelector('a[href="Profil.php"]');
  if (profilLink) profilLink.textContent = translations[lang].profile;
  const aideLink = document.querySelector('a[href="Aide.php"]');
  if (aideLink) aideLink.textContent = translations[lang].help;
  const filtersH2 = document.querySelector(".form-container h2");
  if (filtersH2) filtersH2.textContent = translations[lang].filters;
  const filterPs = document.querySelectorAll(".form-container p");
  if (filterPs[0] && filterPs[0].lastChild)
    filterPs[0].lastChild.textContent = translations[lang].velo;
  if (filterPs[1] && filterPs[1].lastChild)
    filterPs[1].lastChild.textContent = translations[lang].pmr;
  if (filterPs[2] && filterPs[2].lastChild)
    filterPs[2].lastChild.textContent = translations[lang].electric;
  if (filterPs[3] && filterPs[3].lastChild)
    filterPs[3].lastChild.textContent = translations[lang].free;
  const logout = document.querySelector(".logout");
  if (logout) logout.textContent = translations[lang].logout;
  const footer = document.querySelector("footer");
  if (footer) footer.textContent = translations[lang].footer;
  const langToggle = document.getElementById("lang-toggle");
  if (langToggle) langToggle.textContent = translations[lang].langToggle;
  localStorage.setItem("lang", lang);
}
