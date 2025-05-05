<?php
session_start();
include "../menu.php";
?>

    <title>Contact Form</title>
    <link rel="stylesheet" href="../css/Contacto.css" />
    <link rel="stylesheet" href="../css/style.css">
    
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>

    <!-- Link Swiper's CSS -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/boxicons@latest/css/boxicons.min.css"/>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
      

    <!-- CSS -->
     <br>
     <br>
    <link rel="stylesheet" href="../Contacto.css" />
    <section class="gallery-section">
  <center><h2 class="gallery-title">¿Cuáles categorías te gustaron? <span>¡Contáctanos!</span></h2></center>

    
    
  </div>
    <div class="container">
      
    
      <img src="img/shape.png" class="square" alt="" />
      <div class="form">
        <div class="contact-info">
          <h3 class="title">Pongámonos en contacto</h3>
          <p class="text">
          "Comprometidos con la excelencia y la satisfacción de nuestros clientes".
          </p>

          <div class="info">
            <div class="information">
              
              <p>Santiago- La Ceibita, Santiago de los Caballeros 51000</p>
            </div>
            <div class="information">
              
              <p>distribuidoralorenzo19@gmail.com</p>
            </div>
            <div class="information">
              
              <p> +1 (829) 653-0243</p>
            </div>
          </div>

          <div class="social-media">
  <p>Conecta con nosotros:</p>
  <div class="social-icons">
    <a href="https://www.instagram.com/distribuidoralorenzo00?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">
      <img src="../img/instagram.png" alt="Instagram" width="35" />
    </a>
    <a href="https://www.facebook.com/distribuidoralorenzo00/" target="_blank">
      <img src="../img/facebook.png" alt="Facebook" width="35" />
    </a>
  </div>
</div>
        </div>
        

        <div class="contact-form">
          
        

          <form action="https://formsubmit.co/distribuidoralorenzo19@gmail.com" method="POST">
            <h3 class="title">Contactanos</h3>
            <div class="input-container">
              <input type="text" name="name" class="input" />
              <label for="">Nombre de Usuario</label>
              <span>Nombre de Usuario</span>
            </div>
            <div class="input-container">
              <input type="email" name="email" class="input" />
              <label for="">Correo electrónico</label>
              <span>Dirección de correo electrónico<</span>
            </div>
            <div class="input-container">
              <input type="tel" name="phone" class="input" />
              <label for="">Número de teléfono</label>
              <span>Número de teléfono</span>
            </div>
            <div class="input-container textarea">
              <textarea name="message" class="input"></textarea>
              <label for="">Mensaje</label>
              <span>Mensaje</span>
            </div>
            <input type="submit" value="Enviar" class="btn" />
          </form>
        </div>
      </div>
    </div>

    <!--::::Pie de Pagina::::::-->
   

<footer class="pie-pagina">
    <div class="grupo-1">
        <div class="box">
            <figure>
                <a href="#">
                    <img src="../img/LOGO.png" alt="Distribuidora Lorenzo">
                </a>
            </figure>
        </div>
        <div class="box">
            <h2>Mnu inferior</h2>
            <p><a href="../index.php">Inicio</a></p>
            <p><a href="#">Contactos</a></p>
            <p><a href="#">Nosotros</a></p>
            <p><a href="#">Políticas de la empresa</a></p>
            <p><a href="#">Políticas de devolución</a></p>
        </div>
        <div class="box">
            <h2>Siguenos</h2>
            <div class="red-social">
                <a href="https://www.facebook.com/distribuidoralorenzo00/"><i class='bx bxl-facebook-circle'></i></a>
                <a href="https://www.instagram.com/distribuidoralorenzo00?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="><i class='bx bxl-instagram'></i></a>
                
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


    <script src="../js/contacto.js"></script>
    <script src="../script.js"></script>
  </body>
</html>