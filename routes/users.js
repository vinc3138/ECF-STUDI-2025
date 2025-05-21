const express = require('express');
const router = express.Router();

// Exemple simple : GET /api/users/
router.get('/', (req, res) => {
  res.json({ message: 'Liste des utilisateurs (exemple)' });
});

module.exports = router;
