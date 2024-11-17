<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/inicio.css">
</head>
<body>
        <!-- Carrusel de Imágenes -->
        <section id="carrusel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../imagenes/kebab-lacasa.jpg" class="d-block w-100" alt="Kebab de la Casa">
                <div class="carousel-caption d-none d-md-block">
                    <h3>Kebab de la Casa</h3>
                    <p>Disfruta de nuestro kebab tradicional, lleno de sabor auténtico.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../imagenes/kebab-personalizado.jpg" class="d-block w-100" alt="Kebab Personalizado">
                <div class="carousel-caption d-none d-md-block">
                    <h3>Kebab Personalizado</h3>
                    <p>Elige tus ingredientes favoritos y crea tu kebab perfecto.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="../imagenes/Kebab_singluten.jpg" class="d-block w-100" alt="Kebab sin Gluten">
                <div class="carousel-caption d-none d-md-block">
                    <h3>Kebab sin Gluten</h3>
                    <p>Delicioso kebab adaptado para quienes prefieren una opción sin gluten.</p>
                </div>
            </div>
        </div>
        <a class="carousel-control-prev" href="#carrusel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
        </a>
        <a class="carousel-control-next" href="#carrusel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Siguiente</span>
        </a>
    </section>

    <!-- Sección Informativa -->
    <section id="info" class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <img src="../imagenes/kebab-ingredientes.jpg" class="img-fluid rounded" alt="Ingredientes frescos">
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <div>
                    <h2 class="section-title">Ingredientes Frescos y de Calidad</h2>
                    <p class="section-description">
                        Nuestros kebabs se preparan con ingredientes frescos y de calidad. Desde la carne hasta las salsas,
                        cuidamos cada detalle para ofrecerte un kebab delicioso, ya sea de la casa, personalizado o sin gluten.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- Testimonios -->
    <section id="testimonios" class="bg-light py-5">
        <div class="container text-center">
            <h2 class="section-title">Opiniones de Nuestros Clientes</h2>
            <p class="section-description">
                Lee lo que dicen nuestros clientes y descubre por qué somos su kebab favorito.
            </p>
            <div class="row">
                <div class="col-md-4">
                    <blockquote class="blockquote">
                        <p>"El kebab de la casa tiene un sabor increíble, perfecto en cada bocado."</p>
                        <footer class="blockquote-footer">Juan Pérez</footer>
                    </blockquote>
                </div>
                <div class="col-md-4">
                    <blockquote class="blockquote">
                        <p>"Me encanta la opción personalizada, ¡siempre puedo probar algo diferente!"</p>
                        <footer class="blockquote-footer">María López</footer>
                    </blockquote>
                </div>
                <div class="col-md-4">
                    <blockquote class="blockquote">
                        <p>"Es genial encontrar un kebab sin gluten tan sabroso y con ingredientes frescos."</p>
                        <footer class="blockquote-footer">Carlos Ruiz</footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>
</body>
</html>



