// Configuration de production (Vercel) : remplace environment.ts au build
export const environment = {
  production: true,
  // Adresse de l'API Laravel sur Render (garder /api à la fin)
  apiUrl: 'https://rapport-final.onrender.com/api',
  etablissement: "Institut Supérieur d'Informatique",
  sigle: 'ISI',
  signataire: 'La Direction des études',
  demo: false,
};