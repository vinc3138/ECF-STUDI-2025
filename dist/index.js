"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = __importDefault(require("express"));
const pg_1 = require("pg");
const dotenv_1 = __importDefault(require("dotenv"));
dotenv_1.default.config();
const app = (0, express_1.default)();
const port = process.env.PORT || 3000;
const pool = new pg_1.Pool({
    connectionString: process.env.DATABASE_URL,
    ssl: process.env.NODE_ENV === 'production' ? { rejectUnauthorized: false } : false,
});
pool.connect()
    .then(() => console.log('✅ Connexion PostgreSQL réussie'))
    .catch((err) => {
    console.error('❌ Erreur de connexion PostgreSQL :', err);
    process.exit(1);
});
app.use(express_1.default.json());
app.get('/', (_req, res) => {
    res.send('Bienvenue sur l’API de covoiturage 🚗');
});
app.get('/health', (_req, res) => {
    res.status(200).send('OK');
});
app.get('/users', (_req, res) => __awaiter(void 0, void 0, void 0, function* () {
    try {
        const result = yield pool.query('SELECT * FROM users');
        res.json(result.rows);
    }
    catch (err) {
        console.error('Erreur requête /users :', err);
        res.status(500).send('Erreur serveur');
    }
}));
app.listen(port, () => {
    console.log(`🚀 Serveur lancé sur le port ${port}`);
});
