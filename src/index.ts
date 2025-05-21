import express, { Request, Response } from 'express';
import { Pool } from 'pg';
import dotenv from 'dotenv';

dotenv.config();

const app = express();
const port = process.env.PORT || 3000;

const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
  ssl: process.env.NODE_ENV === 'production' ? { rejectUnauthorized: false } : false,
});

pool.connect()
  .then(() => console.log('✅ Connexion PostgreSQL réussie'))
  .catch((err) => {
    console.error('❌ Erreur de connexion PostgreSQL :', err);
    process.exit(1);
  });

app.use(express.json());

app.get('/', (_req: Request, res: Response) => {
  res.send('Bienvenue sur l’API de covoiturage 🚗');
});

app.get('/health', (_req: Request, res: Response) => {
  res.status(200).send('OK');
});

app.get('/users', async (_req: Request, res: Response) => {
  try {
    const result = await pool.query('SELECT * FROM users');
    res.json(result.rows);
  } catch (err) {
    console.error('Erreur requête /users :', err);
    res.status(500).send('Erreur serveur');
  }
});

app.listen(port, () => {
  console.log(`🚀 Serveur lancé sur le port ${port}`);
});

const mongoose = require('mongoose');

mongoose.connect(process.env.MONGODB_URL, {
  useNewUrlParser: true,
  useUnifiedTopology: true,
}).then(() => {
  console.log('MongoDB connecté');
}).catch(err => {
  console.error('Erreur connexion MongoDB :', err);
});

const express = require('express');
const { MongoClient } = require('mongodb');
const cors = require('cors');

const app = express();
app.use(cors()); // Autorise les appels depuis le frontend

const uri = "mongodb+srv://admin:admin@cluster0.ygqxdsj.mongodb.net/?retryWrites=true&w=majority";
const client = new MongoClient(uri);

async function start() {
  try {
    await client.connect();
    console.log("✅ Connecté à MongoDB Atlas !");
    const db = client.db('plateforme_covoiturage');

    // Route 1 : Nombre de covoiturages par jour
    app.get('/covoiturages-par-jour', async (req, res) => {
      try {
        const result = await db.collection('covoiturages').aggregate([
          {
            $group: {
              _id: { $dateToString: { format: "%Y-%m-%d", date: "$date" } },
              totalCovoiturages: { $sum: 1 }
            }
          },
          { $sort: { "_id": 1 } }
        ]).toArray();
        res.json(result);
      } catch (err) {
        res.status(500).json({ error: err.message });
      }
    });

    // Route 2 : Crédit gagné par jour
    app.get('/credits-par-jour', async (req, res) => {
      try {
        const result = await db.collection('covoiturages').aggregate([
          {
            $group: {
              _id: { $dateToString: { format: "%Y-%m-%d", date: "$date" } },
              totalCredit: { $sum: "$creditGagne" }
            }
          },
          { $sort: { "_id": 1 } }
        ]).toArray();
        res.json(result);
      } catch (err) {
        res.status(500).json({ error: err.message });
      }
    });

    // Route 3 : Total des crédits gagnés
    app.get('/total-credit', async (req, res) => {
      try {
        const result = await db.collection('covoiturages').aggregate([
          {
            $group: {
              _id: null,
              totalCredit: { $sum: "$creditGagne" }
            }
          }
        ]).toArray();
        res.json(result[0] || { totalCredit: 0 });
      } catch (err) {
        res.status(500).json({ error: err.message });
      }
    });

// Route pour insérer des données de test
app.get('/ajouter-exemples', async (req, res) => {
  try {
    const exempleData = [
      { date: new Date("2025-05-18"), creditGagne: 10 },
      { date: new Date("2025-05-18"), creditGagne: 20 },
      { date: new Date("2025-05-19"), creditGagne: 15 },
      { date: new Date("2025-05-20"), creditGagne: 30 },
      { date: new Date("2025-05-20"), creditGagne: 25 }
    ];

    await db.collection('covoiturages').insertMany(exempleData);
    res.send({ message: '✅ Données insérées avec succès !' });
  } catch (err) {
    console.error("Erreur d'insertion :", err);
    res.status(500).send({ error: 'Erreur lors de l\'insertion des données' });
  }
});

const PORT = 4000; // port différent de 3000 pour éviter conflit
app.listen(PORT, () => {
  console.log(`🚀 API lancée sur http://localhost:${PORT}`);
});

  } catch (err) {
    console.error("❌ Erreur de connexion MongoDB :", err);
  }
}

start();
