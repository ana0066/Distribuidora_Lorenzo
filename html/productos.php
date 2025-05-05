<?php 
session_start(); 
include '../menu.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="../css/productos.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/carrito.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <main>
        <!-- Products Section -->
      <section class="contenedorProductos">
      <h2>Productos</h2>

      <div class="filtro-productos">
        <label for="filtroCategoria">Filtrar por categoría:</label>
        <select id="filtroCategoria">
          <option value="">Todas</option>
          <option value="mobiliaria">Mobiliaria</option>
          <option value="vajilla">Vajilla</option>
          <option value="decoraciones">Decoraciones</option>
          <option value="herramientas">Herramientas</option>
          <option value="electrodomesticos">Electrodomésticos</option>
        </select>
      </div>

      <div id="productosContainer" class="grid-productos">
        <!-- JS inyecta aquí .card-producto -->
      </div>

      </section>
    </main>

    <div id="cartModal" class="cart-modal">
        <h2>Tu carrito</h2>
        <div id="cartItems"></div>
        <div class="cart-footer">
            <p>Total artículos: <span id="cart-count">0</span></p>
            <a href="checkout.php" class="button">Ir a Checkout</a>
        </div>
    </div>
    </main>

</body>
</html>