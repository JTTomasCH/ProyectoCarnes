const express = require('express');
const bodyParser = require('body-parser');
const axios = require('axios'); // Para enviar solicitudes HTTP

const app = express();
app.use(bodyParser.urlencoded({ extended: true }));

app.post('/send-reset-link', async (req, res) => {
    const { email } = req.body;
    
    try {
        // Enviar solicitud POST al script PHP para verificar el correo en la base de datos
        const response = await axios.post('http://localhost/verify-email.php', { email });
        
        if (response.data.success) {
            res.send('Se ha enviado un enlace de restablecimiento a su correo electrónico.');
        } else {
            res.send('No se encontró el correo electrónico en la base de datos.');
        }
    } catch (error) {
        console.error(error);
        res.send('Hubo un error al procesar la solicitud.');
    }
});

app.listen(3000, () => {
    console.log('Servidor escuchando en http://localhost:3000');
});
