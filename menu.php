<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Distribuidora Lorenzo</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/carrito.css">
  <link rel="stylesheet" href="../css/productos.css">
  <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<header>
  <div class="principal-info">
    <div class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></div>
    <div class="info-logo"><img src="../img/logo.png" alt="Logo de Distribuidora Lorenzo"></div>
    <div class="info-nombre"><h1>Distribuidora Lorenzo</h1></div>
    <div class="info-telefono"><p><i class="fas fa-phone"></i> (809) 906-3559</p></div>
  </div>
    <div class="principal-info">

        <div class="menu-toggle" id="menu-toggle">
            <i class="fas fa-bars"></i>
        </div>

        <div class="info-logo">
            <img src="../img/logo.png" alt="Logo de Distribuidora Lorenzo">
        </div>
        <div class="info-nombre">
            <h1>Distribuidora Lorenzo</h1>
        </div>
        <div class="info-telefono">
            <p><i class="fas fa-phone"></i> (809) 506-3559</p>
        </div>

    </div>

  <div class="navegador-info">
    <nav class="nav" id="nav">
      <ul class="nav-links">
        <li><a href="../html/index.php">Inicio</a></li>
        <li><a href="../html/nosotros.php">Nosotros</a></li>
        <li><a href="../html/productos.php">Productos</a></li>
        <li><a href="../html/contacto.php">Contacto</a></li>
        <?php 
          if (isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['admin','superadmin'])) {
            echo "<li><a href='../admin/admin.php'>Administrar productos</a></li>";
          }
          if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
            echo "<li><a href='../html/admin_usuarios.php'>Administrar Usuarios</a></li>";
          }
        ?>
      </ul>
    </nav>

    <div class="icons">
      <div class="buscador-container">
        <input type="text" id="buscador" class="buscador" placeholder="Buscar...">
        <i class="fas fa-search"></i>
        <ul id="sugerencias" class="sugerencias"></ul>
      </div>

      <?php 
        if (isset($_SESSION['usuario'])) {
          echo "<a class='logoutBtn' href='#' onclick='confirmLogout(event)'><i class='fas fa-sign-out-alt'></i></a>";
          echo "<span class='username'>" . htmlspecialchars($_SESSION['usuario']) . "</span>";
        } else {
          echo "<a href='../php/login.php'><i class='fas fa-user'></i></a>";
        }
      ?>

      <!-- Carrito Icon -->
      <i id="cartIcon" class="fas fa-shopping-cart"></i>
      <span id="cart-count">0</span>
    </div>
  </div>
</header>

<!-- Aside Carrito -->
<aside id="asideCarrito" class="aside-carrito">
  <div class="carrito-header">
    <h3>Mi Carrito</h3>
    <button id="btnCerrarCarrito">×</button>
  </div>
  <div id="carritoItems" class="carrito-items">
    <!-- Productos vía AJAX -->
  </div>
  <div class="carrito-footer">
    <p>Total: <span id="totalCarrito">$0.00</span></p>
    <a href="../html/checkout.php" class="btn-checkout">Checkout</a>
  </div>
</aside>

<script>
  // Menú responsive
  (function(){
    const btnMenu = document.getElementById('menu-toggle');
    const nav     = document.getElementById('nav');
    btnMenu.addEventListener('click', ()=> nav.classList.toggle('active'));
  })();

  // Confirmar logout
  function confirmLogout(e){
    e.preventDefault();
    if (confirm("¿Estás seguro de que deseas cerrar sesión?")) {
      window.location.href = '../php/logout.php';
    }
  }

  // Carrito AJAX
  (function(){
    const cartIcon = document.getElementById('cartIcon');
    const aside    = document.getElementById('asideCarrito');
    const btnClose = document.getElementById('btnCerrarCarrito');
    const list     = document.getElementById('carritoItems');
    const countEl  = document.getElementById('cart-count');
    const totalEl  = document.getElementById('totalCarrito');

    cartIcon.addEventListener('click', ()=>{
      aside.classList.add('mostrar');
      loadCart();
    });
    btnClose.addEventListener('click', ()=> aside.classList.remove('mostrar'));

    function loadCart(){
      fetch('../carrito/obtener_carrito.php')
        .then(r => r.json())
        .then(data => {
          list.innerHTML = '';
          let total = 0, cnt = 0;
          data.items.forEach(it => {
            const valor = parseFloat(it.valor);
            const sub   = parseFloat(it.subtotal);
            total += sub; cnt++;
            const div = document.createElement('div');
            div.className = 'item-carrito';
            div.innerHTML = `
              <img src="${it.urlImagen}" alt="${it.nombre}">
              <div class="item-info">
                <h4>${it.nombre}</h4>
                <p class="precio">RD$${valor.toFixed(2)}</p>
                <p class="subtotal">Sub: RD$${sub.toFixed(2)}</p>
                <input type="number" min="1" value="${it.cantidad}" data-id="${it.id_carrito}">
              </div>
              <button class="eliminar" data-id="${it.id_carrito}">×</button>
            `;
            list.appendChild(div);
          });
          countEl.textContent = cnt;
          totalEl.textContent = 'RD$' + total.toFixed(2);

          // Bind eventos
          list.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('change', e => {
              updateItem(e.target.dataset.id, parseInt(e.target.value, 10));
            });
          });
          list.querySelectorAll('.eliminar').forEach(btn => {
            btn.addEventListener('click', e => {
              removeItem(e.target.dataset.id);
            });
          });
        });
    }

    function updateItem(id, qty){
      fetch('../carrito/actualizar_carrito.php', {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({ id_carrito: id, cantidad: qty })
      })
      .then(r => r.json())
      .then(resp => { if (resp.ok) loadCart(); });
    }

    function removeItem(id){
      fetch('../carrito/eliminar_carrito.php?id=' + id)
        .then(r => r.json())
        .then(resp => { if (resp.ok) loadCart(); });
    }
  })();
</script>

<script src="../js/script.js"></script>
<script src="../js/slider.js"></script>
</body>
</html>
