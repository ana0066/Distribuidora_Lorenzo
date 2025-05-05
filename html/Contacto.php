<?php
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
      href="https://unpkg.com/boxicons@latest/css/boxicons.min.css"
    />

    <!-- CSS -->
    <link rel="stylesheet" href="../Contacto.css" />
    

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
<center>
          <div class="social-media">
            <p>Conecta con nosotros:</p>
            <div class="social-icons">
            <a href="https://www.facebook.com/distribuidoralorenzo00/">
              <i class='bx bxl-facebook-circle' style='color:#ece0e0' ></i>
              </a>
              <a href="https://www.instagram.com/distribuidoralorenzo00?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">
              <i class='bx bxl-instagram' style='color:#ece0e0'  ></i>
              </a>
            </div>
            
          </div>
        </div>
        </center>

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
                        <img src="../img/LOGO.png" alt="Distribuidora/Lorenzo">
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

    <script src="../js/contacto.js"></script>
    <script src="../script.js"></script>
  </body>
</html>