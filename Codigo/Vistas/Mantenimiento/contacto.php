<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/contacto.css">
</head>
<body>
<main>
    <section id="contacto" class="container mt-5" >
        <h1 class="section-title">Contacto</h1>
        <div class="row">
            <!-- Información de contacto -->
            <div class="col-md-6">
                <h2>¿Tienes alguna pregunta?</h2>
                <p class="section-description">
                    Si tienes alguna consulta sobre nuestros productos, pedidos o cualquier otra duda, ¡no dudes en contactarnos! 
                    Estamos aquí para ayudarte.
                </p>
                <p><strong>Dirección:</strong> Calle Ficticia 123, Jaén, España</p>
                <p><strong>Teléfono:</strong> +123 456 789</p>
                <p><strong>Email:</strong> contacto@esencialkebab.com</p>

                <!-- Mapa (opcional, puedes integrar uno de Google Maps si lo deseas) -->
                <div class="map-container mt-4">
                    <iframe src="https://www.google.com/maps/embed?pb=..." width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>

            <!-- Formulario de contacto -->
            <div class="col-md-6">
                <h2>Envíanos un mensaje</h2>
                <form action="enviar-mensaje.php" method="POST">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Tu correo electrónico" required>
                    </div>
                    <div class="form-group">
                        <label for="mensaje">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" placeholder="Tu mensaje" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar mensaje</button>
                </form>
            </div>
        </div>
    </section>
</main>
</body>
</html>
