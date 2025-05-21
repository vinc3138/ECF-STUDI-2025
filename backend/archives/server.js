const express = require('express');
const connectDB = require('./db'); // chemin vers ton fichier db.js

const app = express();

// Connexion à MongoDB
connectDB();

app.use(express.json());

// Tes routes ici

const PORT = process.env.PORT || 4000;
app.listen(PORT, () => {
  console.log(`Serveur démarré sur le port ${PORT}`);
});