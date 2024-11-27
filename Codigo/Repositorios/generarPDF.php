<?php
const express = require('express');
const nodemailer = require('nodemailer');
const bodyParser = require('body-parser');
const { PDFDocument } = require('pdf-lib');
const fs = require('fs');
const path = require('path');

// Crear una instancia de la aplicación Express
const app = express();

// Configuración para que Express pueda leer el cuerpo de las solicitudes en formato JSON
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));

// Ruta para simular la compra y generar el PDF
app.post('/generar-pdf', async (req, res) => {
    const { usuarioEmail, nombreUsuario, carrito, totalPrecio } = req.body;

    // Crear un nuevo documento PDF
    const pdfDoc = await PDFDocument.create();
    const page = pdfDoc.addPage([600, 400]);
    const { width, height } = page.getSize();

    // Agregar contenido al PDF (encabezado, nombre del usuario, carrito, etc.)
    page.drawText(`Ticket de Compra`, { x: 50, y: height - 50, size: 20 });
    page.drawText(`Nombre: ${nombreUsuario}`, { x: 50, y: height - 80, size: 12 });
    page.drawText(`Email: ${usuarioEmail}`, { x: 50, y: height - 100, size: 12 });

    // Detalles de los productos
    let yPosition = height - 140;
    carrito.forEach(item => {
        page.drawText(`${item.nombre}: ${item.cantidad} x ${(item.precioUnitario).toFixed(2)} €`, { x: 50, y: yPosition, size: 12 });
        yPosition -= 20;
    });

    page.drawText(`Total: ${totalPrecio.toFixed(2)} €`, { x: 50, y: yPosition - 20, size: 14 });

    // Guardar el PDF en un buffer
    const pdfBytes = await pdfDoc.save();

    // Guardar el PDF en el disco (opcional)
    const pdfPath = path.join(__dirname, 'ticket.pdf');
    fs.writeFileSync(pdfPath, pdfBytes);

    // Enviar el PDF al usuario (enviar por correo)
    const transporter = nodemailer.createTransport({
        service: 'gmail',
        auth: {
            user: 'tu-email@gmail.com', // Tu correo de Gmail
            pass: 'tu-contraseña' // Tu contraseña o contraseña de aplicación de Gmail
        }
    });

    const mailOptions = {
        from: 'tu-email@gmail.com',
        to: usuarioEmail,
        subject: 'Ticket de Compra',
        text: 'Gracias por tu compra. Adjunto encontrarás tu ticket.',
        attachments: [
            {
                filename: 'ticket.pdf',
                path: pdfPath
            }
        ]
    };

    // Enviar el correo con el archivo adjunto
    transporter.sendMail(mailOptions, (error, info) => {
        if (error) {
            return res.status(500).send('Error al enviar el correo: ' + error);
        }
        res.status(200).send('Compra simulada y PDF generado y enviado al correo.');
    });
});

// Iniciar el servidor
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Servidor corriendo en el puerto ${PORT}`);
});
