const express = require('express');
const bodyParser = require('body-parser');
const sqlite3 = require('sqlite3').verbose();
const cors = require('cors');

const app = express();
const db = new sqlite3.Database('./web.db');

app.use(cors());
app.use(bodyParser.json());

// Crear la tabla de usuarios
db.serialize(() => {
  db.run("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, nombres TEXT, apellidos TEXT, correo TEXT, telefono TEXT, contrasena TEXT)");
});

// Endpoint para registrar un usuario
app.post('/api/register', (req, res) => {
  const { nombres, apellidos, correo, telefono, contrasena } = req.body;
  db.run("INSERT INTO users (nombres, apellidos, correo, telefono, contrasena) VALUES (?, ?, ?, ?, ?)", [nombres, apellidos, correo, telefono, contrasena], function(err) {
    if (err) {
      return res.status(500).json({ error: err.message });
    }
    res.status(201).json({ id: this.lastID });
  });
});

// Iniciar el servidor
app.listen(5000, () => {
  console.log('Servidor corriendo en http://localhost:5000');
});
