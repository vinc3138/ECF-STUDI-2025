const express = require('express');
const connectDB = require('./db');

const app = express();

connectDB();

app.use(express.json());

// Import et utilisation des routes users
const userRoutes = require('./routes/users');
app.use('/api/users', userRoutes);

app.get('/health', (req, res) => {
  res.status(200).send('OK');
});

const port = process.env.PORT || 3000;
app.listen(port, () => {
  console.log(`Server running on port ${port}`);
});
