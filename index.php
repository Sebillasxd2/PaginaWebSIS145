<?php
session_start();

// Si el usuario no está logueado como cliente, redirígelo al login
if (!isset($_SESSION['cliente_id']) || $_SESSION['rol'] !== 'cliente') {
    header('Location: login.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tienda Gamer SIS145</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>

    <!-- Encabezado con menú desplegable -->
    <header>
        <div class="header-title">
            <img src="Imagenes/logo.png" alt="Logo Gamer SIS145" class="header-logo">
            <h1>Gamer SIS145</h1>
        </div>
        <nav>
            <a href="#inicio" class="nav-link">Inicio</a>
            
            <div class="dropdown">
                <a href="#tienda" class="nav-link">Tienda</a>
                <div class="dropdown-content">
                    <a href="#tienda" data-categoria="Audio">Audio</a>
                    <a href="#tienda" data-categoria="Periféricos">Periféricos</a>
                    <a href="#tienda" data-categoria="Cámaras">Cámaras</a>
                    <a href="#tienda" data-categoria="">Todos los Productos</a>
                </div>
            </div>
            <a href="#ubicacion" class="nav-link">Ubicacion</a>
            <a href="#about" class="nav-link">Sobre Nosotros</a>
            <a href="#contacto" class="nav-link">Contacto</a>
        </nav>
    </header>

    <!-- Slider en la sección de inicio -->
    <section id="inicio">
        <div class="slider">
            <!-- Slide 1: Bienvenida -->
            <div class="slide active" style="background-image: url('Imagenes/inicio.jpg');">
                <div class="slide-content">
                    <h2>BIENVENIDO A GAMER SIS145</h2>
                    <p>Ofrecemos los mejores mouses, teclados, cámaras y micrófonos para la comunidad gamer. Tecnología de alto rendimiento al alcance de todos los jugadores.</p>
                </div>
            </div>
            
            <!-- Slide 2: Producto destacado -->
            <div class="slide" style="background-image: url('Imagenes/producto.jpg');">
                <div class="slide-content">
                    <h2>PRODUCTO DESTACADO</h2>
                    <div class="featured-product">
                        <img src="Imagenes/mouse-gamer.png" alt="Mouse Gamer HyperX" class="featured-product-image">
                        <div class="featured-product-info">
                            <span class="best-seller-badge">MÁS VENDIDO</span>
                            <h3>Mouse Gamer HyperX Pulsefire Dart</h3>
                            <p class="description">El HyperX Pulsefire Dart™ es un mouse inalámbrico para gaming con tecnología Qi para carga inalámbrica, sensor PixArt 3389 y switches Omron.</p>
                            <p class="price">$99.99</p>
                            <a href="#tienda" class="button">Ver en Tienda</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Slide 3: Horarios de atención -->
            <div class="slide" style="background-image: url('Imagenes/horarios.jpg');">
                <div class="slide-content">
                    <h2>HORARIOS DE ATENCIÓN</h2>
                    <table class="opening-hours-table">
                        <tr>
                            <th>Día</th>
                            <th>Horario</th>
                        </tr>
                        <tr>
                            <td>Lunes a Viernes</td>
                            <td>9:00 - 18:00</td>
                        </tr>
                        <tr>
                            <td>Sábados</td>
                            <td>10:00 - 14:00</td>
                        </tr>
                        <tr>
                            <td>Domingos</td>
                            <td>Cerrado</td>
                        </tr>
                    </table>
                    <p style="margin-top: 1.5rem;">¡Estamos para servirte! Contáctanos en nuestros horarios de atención.</p>
                </div>
            </div>
            
            <div class="slide-buttons">
                <button class="slide-button active" data-slide="0"></button>
                <button class="slide-button" data-slide="1"></button>
                <button class="slide-button" data-slide="2"></button>
            </div>
        </div>
    </section>

    <!-- Sección del Carrito -->
    <section id="carrito">
        <h2>Tu Carrito de Compras</h2>
        <table id="tabla-carrito">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="contenido-carrito">
                <tr>
                    <td colspan="5">Tu carrito está vacío</td>
                </tr>
            </tbody>
        </table>
        <div id="total-carrito">
            <p>Total: $<span id="total-amount">0.00</span></p>
        </div>
        <button id="vaciar-carrito">Vaciar Carrito</button>
    </section>

    <!-- Sección de Tienda -->
    <section id="tienda">
        <h2>Nuestros productos disponibles</h2>
        <div id="container"></div>
    </section>

    <!-- Sección de Ubicación -->
    <section id="ubicacion">
        <h2>¿Dónde estamos ubicados?</h2>
        <p id="descripcion-ubicacion">Cargando...</p>
        <div class="map-container" style="text-align: center;">
            <iframe id="mapa-ubicacion"
                width="100%" 
                height="400" 
                style="border:0; border-radius: 12px;" 
                allowfullscreen="" 
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </section>

    <!-- Sección Acerca de Nosotros -->
    <section id="about" class="about">
        <h2>Acerca de Nosotros</h2>
        <p>Gamer SIS145 es una empresa boliviana comprometida con ofrecer lo mejor en tecnología gamer. Ya seas un jugador casual o competitivo, tenemos el equipo perfecto para mejorar tu experiencia de juego. ¡La pasión gamer nos une!</p>
        
        <!-- Nuestra Historia -->
        <div class="history">
            <h3>Nuestra Historia</h3>
            <p>Fundada en 2025 en Bolivia, Gamer SIS145 nació de la pasión compartida por los videojuegos y la tecnología de última generación. Lo que comenzó como un pequeño proyecto entre amigos gamers, hoy se ha convertido en un referente de la industria gaming nacional, ofreciendo productos de calidad y asesoramiento especializado.</p>
        </div>
        
        <!-- Nuestros Logros -->
        <div class="achievements">
            <h3>Nuestros Logros</h3>
            <div class="achievements-container">
                <div class="achievement-card">
                    <h4>500+</h4>
                    <p>Clientes Satisfechos</p>
                </div>
                <div class="achievement-card">
                    <h4>9</h4>
                    <p>Departamentos con Envíos</p>
                </div>
                <div class="achievement-card">
                    <h4>100%</h4>
                    <p>Productos Garantizados</p>
                </div>
                <div class="achievement-card">
                    <h4>24/7</h4>
                    <p>Soporte al Cliente</p>
                </div>
            </div>
        </div>
        
        <!-- Horarios de Atención -->
        <div class="opening-hours">
            <h3>Horarios de Atención</h3>
            <table class="opening-hours-table">
                <tr>
                    <th>Día</th>
                    <th>Horario</th>
                </tr>
                <tr>
                    <td>Lunes a Viernes</td>
                    <td>9:00 - 18:00</td>
                </tr>
                <tr>
                    <td>Sábados</td>
                    <td>10:00 - 14:00</td>
                </tr>
                <tr>
                    <td>Domingos</td>
                    <td>Cerrado</td>
                </tr>
            </table>
            <p style="margin-top: 1.5rem; text-align: center;">Durante horarios fuera de atención, puedes contactarnos por WhatsApp y te responderemos lo antes posible.</p>
        </div>
    </section>

    <!-- Sección de Proveedores -->
    <section id="proveedores">
        <h2>Nuestros Proveedores</h2>
        <div id="lista-proveedores" class="proveedores-container">
            <p>Cargando proveedores...</p>
        </div>
    </section>

    <!-- Pie de página -->
    <footer id="contacto">
        <p><strong>Contáctanos por WhatsApp:</strong></p>
        <p>
            <a href="https://wa.me/59177124398" target="_blank">77124398</a> |
            <a href="https://wa.me/59175794549" target="_blank">75794549</a> |
            <a href="https://wa.me/59168652664" target="_blank">68652664</a>
        </p>
        <p>&copy; 2025 Gamer SIS145 - Todos los derechos reservados.</p>
    </footer>
</body>
</html>