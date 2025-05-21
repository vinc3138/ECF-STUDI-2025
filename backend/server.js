require('dotenv').config();

const express = require('express');

const app = express();
const port = process.env.PORT || 4002;

app.use(express.json());

// routes
const userRoutes = require('./routes/users');
app.use('/api/users', userRoutes);

app.get('/health', (req, res) => {
  res.status(200).send('OK');
});

app.listen(port, () => {
  console.log(`Server running on port ${port}`);
});
