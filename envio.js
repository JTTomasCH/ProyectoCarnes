const express = require('express');
const nodemailer = require('nodemailer');
const crypto = require('crypto');
const mysql = require('mysql2/promise');
const bodyParser = require('body-parser');
const bcrypt = require('bcrypt');

const app = express();
app.use(bodyParser.json());

// Configuración de la conexión a MySQL
const db = mysql.createPool({
    host: '127.0.0.1',
    user: 'root',
    password: '',
    database: 'CarnesByr'
});

// Configuración del transportador de nodemailer
const transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: 'tuCorreo@gmail.com',
        pass: 'tuContraseña',
    },
});

// Ruta para registrar usuarios
app.post('/api/register', async (req, res) => {
    const { nombre, apellidos, email, telefono, password } = req.body;

    // Hash de la contraseña
    const hashedPassword = await bcrypt.hash(password, 10);

    try {
        await db.execute('INSERT INTO usuarios (nombres, apellidos, correo, telefono, contraseña) VALUES (?, ?, ?, ?, ?)', 
        [nombre, apellidos, email, telefono, hashedPassword]);

        res.status(201).send('Usuario registrado.');  // Aquí ya no se envía correo de activación
    } catch (error) {
        res.status(500).send('Error al registrarse. Intenta nuevamente.');
    }
});

// Iniciar el servidor
app.listen(3000, () => {
    console.log('Servidor en ejecución en http://localhost:3000');
});
