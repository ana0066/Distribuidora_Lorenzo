<?php
session_start();
include "../menu.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribuidora Lorenzo</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/carrito.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>



<!--SLIDER-->

<div class="slider-container">
  <div class="slider" id="slider">

    <!-- Slide 1 -->
    <div class="slide active">
      <img src="../img/Tienda.jpg" alt="Imagen 1" />
      <div class="image-data">
       
        <h2>Nuestra Variedades <br />de platos</h2>
        <a href="#" class="button">Más información</a>
      </div>
    </div>

    <!-- Slide 2 -->
    <div class="slide">
      <img src="../img/Electo.jpg" alt="Imagen 2" />
      <div class="image-data">
     
        <h2>Nuestra Variedades<br /> De Electródomesticos</h2>
        <a href="#" class="button">Más información</a>
      </div>
    </div>

    <!-- Slide 3 -->
    <div class="slide">
      <img src="../img/Cocina.jpg" alt="Imagen 3" />
      <div class="image-data">
        <span class="text"></span>
        <h2>Nuestra Variedades<br />para la Cocina</h2>
        <a href="#" class="button">Más información</a>
      </div>
    </div>

  </div>

  <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
  <button class="next" onclick="moveSlide(1)">&#10095;</button>
  <!--Codigo para que las fotos salgan en automatico Inicio -->
  <script>
  let currentIndex = 0;
  const slides = document.querySelectorAll('.slide');
  const totalSlides = slides.length;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.remove('active');
    });
    slides[index].classList.add('active');
    document.getElementById('slider').style.transform = `translateX(-${index * 100}%)`;
  }

  function moveSlide(n) {
    currentIndex = (currentIndex + n + totalSlides) % totalSlides;
    showSlide(currentIndex);
  }

  // Cambio automático cada 5 segundos
  setInterval(() => {
    moveSlide(1);
  }, 5000);

  // Mostrar la primera slide al cargar
  showSlide(currentIndex);
</script>
<!--Codigo para que las fotos salgan en automatico final -->

</div>


<!--cartas productos -->

<section class="cards-productos">
<h2 class="titulo-productos">DISFRUTA DE TODAS NUESTRAS VARIEDADES</h2>
    
    <div class="contenedor">
        <div class="categoria">
            <img src="../img/Mobiliarida.jpeg" alt="Mobiliaria">
            <p>MOBILIARIA</p>
        </div>
        <div class="categoria">
            <img src="../img/PRODUCTOS PARA EL HOGAR.jpeg" alt="Productos para el hogar">
            <p>HERRAMIENTAS</p>
        </div>
        <div class="categoria">
            <img src="../img/Decoracion de hogar.jpeg" alt="Decoraciones para el hogar">
            <p>DECORACIONES PARA EL HOGAR</p>
        </div>
        <div class="categoria">
            <img src="../img/eletrodomestico.jpeg" alt="Electrodomésticos">
            <p>ELECTRODOMÉSTICOS</p>
        </div>
        <div class="categoria">
            <img src="../img/vaji.jpeg" alt="Vajillas">
            <p>VAJILLAS</p>
        </div>
    </div>
</section>

<body>
  <section class="section-container">
    <h2><strong>Nuestros Mejores Productos</strong></h2>
    <p class="description"></p>

    <div class="product-grid">
      <!-- Tarjeta 1 -->
      <div class="card">
        <img src="../img/Grequita.png" alt="Silla blanca">
        <h3>Greca</h3>
        <p class="sub">Informacion</p>
        <p class="price">$600</p>
        <button class="add-btn">Ver más</button>
      </div>

      <!-- Tarjeta 2 -->
      <div class="card">
        <img src="../img/vajilla.jpeg" alt="Silla gris">
        <h3>Vajillas</h3>
        <p class="sub"></p>
        <p class="price">$300</p>
        <button class="add-btn">Ver más</button>
      </div>

      <!-- Promocional -->
      <div class="promo">
        <div class="overlay">
          <h2></h2>
          <p>PRODUCTOS</p>
          <button class="info-btn">Informacion</button>
        </div>
      </div>

      <!-- Promocional duplicado -->
      <div class="promo">
        <div class="overlay">
          <h2></h2>
          <p>PRODUCTOS</p>
          <button class="info-btn">Informacion</button>
        </div>
      </div>

      <!-- Tarjeta 3 -->
      <div class="card">
        <img src="../img/estufa.png" alt="Silla rosada">
        <h3>Estufa</h3>
        <p class="sub">calidad premium</p>
        <p class="price">$15,000</p>
        <button class="add-btn">Ver más</button>
      </div>

      <!-- Tarjeta 4 -->
      <div class="card">
        <img src="../img/cama.png" alt="Silla verde">
        <h3>Cama</h3>
        <p class="sub">calidad premium</p>
        <p class="price">$20,000</p>
        <button class="add-btn">Ver más</button>
      </div>
    </div>
  </section>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>

<br>
<br>
<!--::::Pie de Pagina::::::-->
<footer class="pie-pagina">
        <div class="grupo-1">
            <div class="box">
                <figure>
                    <a href="#">
                        <img src="../img/LOGO.png" alt="Distribuidora/Lorenzo">
                    </a>
                </figure>
            </div>
            <div class="box">
                <b><h2>Menú Inferior</h2>
                <p>Inicio</p>
                <p>Contactos</p>
                <p>Nosotros</p>
                <p>Políticas de la empresa</p>
                <p>Políticas de devolución</p>
            </b>
            </div>
            
            <div class="box">
                <h2>Siguenos</h2>
                <div class="red-social">
                    <a href="https://www.facebook.com/distribuidoralorenzo00/" ><i class='fas fa-facebook' style='color:#ece0e0' ></i></a>
                    <a href="https://www.instagram.com/distribuidoralorenzo00?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" ><i class='fas fa--instagram' style='color:#ece0e0'  ></i></a>
                    <a href="#"><i class='fas fa-twitter'></i></a>
                
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

    <script src="../js/productos.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/slider.js"></script>
    <script src="../js/carrito.js"></script>
</body>
</html>