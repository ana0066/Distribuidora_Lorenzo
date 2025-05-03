<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora Lorenzo</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header>
    <div class="principal-info">

    <div class="menu-toggle" id="menu-toggle">
        <i class="fas fa-bars"></i>
    </div>

        <div class="info-logo">
            <img src="img/logo.png" alt="Logo de Distribuidora Lorenzo">
        </div>
        <div class="info-nombre">
            <h1>Distribuidora Lorenzo</h1>
        </div>
        <div class="info-telefono">
        <p><i class="fas fa-phone"></i> (809) 906-3559</p>
        </div>

        
    </div>

    <div class="navegador-info">
    <nav class="nav" id="nav">
        <ul class="nav-links">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="html/nosotros.php">Nosotros</a></li>
            <li><a href="html/productos.php">Productos</a></li>
            <li><a href="html/contacto.php">Contacto</a></li>
        </ul>
    </nav>
    

    <div class="icons">

        <div class="buscador-container">
            <input type="text" id="buscador" class="buscador" placeholder="Buscar...">
            <ul id="sugerencias" class="sugerencias"></ul>
        </div>

        <?php 
            if (isset($_SESSION['usuario'])) {
                echo "<a class='searchToggle logoutBtn' id='logoutBtn' href='../php/logout.php'><i class='fas fa-sign-out-alt'></i></a>";
                echo "<span class='username'>" . $_SESSION['usuario'] . "</span>";
            } else {
                echo "<a class='searchToggle' href='php/login.php'><i class='fas fa-user'></i></a>";
            }
        ?>
        <i class="fas fa-shopping-cart" onclick="toggleCart()"></i>
        <span id="cart-count">0</span>
    </div>
    </div>
</header>

<!--<script>
    const toggle = document.getElementById('menu-toggle');
    const nav = document.getElementById('nav');

    toggle.addEventListener('click', () => {
        nav.classList.toggle('active');
    });
</script>-->

<!--SLIDER-->

<div class="slider-container">
  <div class="slider" id="slider">
    <div class="slide active"><img src="img/Mobiliarida.jpeg" alt="Imagen 1"></div>
    <div class="slide"><img src="img/eletrodomestico.jpeg" alt="Imagen 2"></div>
    <div class="slide"><img src="img/Cocina.jpg" alt="Imagen 3"></div>
  </div>

  <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
  <button class="next" onclick="moveSlide(1)">&#10095;</button>
</div>

<section class="cards-productos">
<h2 class="titulo-productos">DISFRUTA DE TODAS NUESTRAS VARIEDADES</h2>
    
    <div class="contenedor">
        <div class="categoria">
            <img src="img/cama.png" alt="Mobiliaria">
            <p>MOBILIARIA</p>
        </div>
        <div class="categoria">
            <img src="img/PRODUCTOS PARA EL HOGAR.jpeg" alt="Productos para el hogar">
            <p>HERRAMIENTAS</p>
        </div>
        <div class="categoria">
            <img src="img/eletrodomestico.jpeg" alt="Decoraciones para el hogar">
            <p>DECORACIONES PARA EL HOGAR</p>
        </div>
        <div class="categoria">
            <img src="img/eletrodomestico.jpeg" alt="Electrodomésticos">
            <p>ELECTRODOMÉSTICOS</p>
        </div>
        <div class="categoria">
            <img src="img/Mobiliarida.jpeg" alt="Vajillas">
            <p>VAJILLAS</p>
        </div>
    </div>
</section>

<section class="inicio-comentarios">
<div class="banner">
            <p>VIVE LA EXPERIENCIA COMPLETA EN DISTRIBUIDORA LORENZO</p>
        </div>
    
        <div class="comentarios">
            <div class="comentario">
                <p class="nombre">DENCEL LAJARA</p>
                <p>Muy mala, no me llegó el biurí que quería.</p>
                <h1><div class="estrellas">★★★★☆</div></h1>
            </div>
    
            <div class="comentario">
                <p class="nombre">CASIMIRA LÓPEZ</p>
                <p>Buenas</p>
                <div class="estrellas">★★★★★</div>
            </div>
    
            <div class="comentario">
                <p class="nombre">SELENA QUINTANILLA</p>
                <p>Siempre hay nuevas opciones y una gran variedad para elegir, los precios son muy accesibles también.</p>
                <div class="estrellas">★★★★☆</div>
            </div>
        </div>
</section>

<!--::::Pie de Pagina::::::-->
<footer class="pie-pagina">
        <div class="grupo-1">
            <div class="box">
                <figure>
                    <a href="#">
                        <img src="img/LOGO.png" alt="Distribuidora/Lorenzo">
                    </a>
                </figure>
            </div>
            <div class="box">
                <b><h2>MENÚ INFERIOR</h2>
                <p>Inicio</p>
                <p>Contactos</p>
                <p>Nosotros</p>
                <p>Políticas de la empresa</p>
                <p>Políticas de devolución</p>
            </b>
            </div>
            
            <div class="box">
                <h2>SIGUENOS</h2>
                <div class="red-social">
                    <a href="https://www.facebook.com/distribuidoralorenzo00/" class="fa fa-facebook"><i class='bx bxl-facebook-circle' style='color:#ece0e0' ></i></a>
                    <a href="https://www.instagram.com/distribuidoralorenzo00?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" class="fa fa-instagram"><i class='bx bxl-instagram' style='color:#ece0e0'  ></i></a>
                    <a href="#" class="fa fa-twitter"><i class='bx bxl-twitter'></i></a>
                
                </div>
                 </div>
            <div class="mapa">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d940.7775665097614!2d-70.6877119476317!3d19.4076411519626!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8eb1cf63e9c85b9b%3A0x7a4fbe5dc63a2545!2sDistribuidora%20Lorenzo!5e0!3m2!1ses!2sdo!4v1741976829787!5m2!1ses!2sdo" width="250" height="240" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
           
            </div>
        </div>
        
        <div class="grupo-2">
            <small>&copy; 2025 <b>Distribuidora Lorenzo</b> - Todos los Derechos Reservados.</small>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script src="js/slider.js"></script>
</body>
</html>