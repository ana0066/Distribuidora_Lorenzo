<?php
include_once '../php/db.php';

$stmt = $conn->prepare("SELECT * FROM products WHERE existencia > 0");
$stmt->execute();
$resultado = $stmt->get_result();

include "../menu.php";
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos | Distribuidora Lorenzo</title>
  <link rel="stylesheet" href="../css/style.css"> 
  <link rel="stylesheet" href="../css/productos.css">
</head>
<body>
  

<main>
  <section class="contenedorProductos">
    <h2>Catálogo de Productos</h2>

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
    <div class="buscador-productos">
  <label for="inputBuscar">Buscar producto:</label>
  <input type="text" id="inputBuscar" placeholder="Ej. cama, vajilla... 🔍">
</div>

    <div id="productosContainer" class="grid-productos">
      <?php while ($producto = $resultado->fetch_assoc()): ?>
        <div class="card-producto" data-categoria="<?= htmlspecialchars($producto['categoria']) ?>">
          <img src="<?= htmlspecialchars($producto['urlImagen']) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
          <div class="info-producto">
            <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
            <p class="precio">$<?= number_format($producto['valor'], 2) ?></p>
            <p class="stock">Stock: <?= $producto['existencia'] ?></p>
            <form method="POST" action="../carrito/agregar_carrito.php">
            <input type="hidden" name="id_producto" value="<?= $producto['id'] ?>">
            <button type="submit">Agregar al carrito</button>
          </form>

          </div>
        </div>
      <?php endwhile; ?>
    </div>

    <p id="mensajeVacio" style="display:none; text-align:center; margin-top: 2rem;">No hay productos en esta categoría.</p>
  </section>
</main>

<script>
  const inputBuscar = document.getElementById('inputBuscar');
  const filtro = document.getElementById('filtroCategoria');
  const productos = document.querySelectorAll('.card-producto');
  const mensaje = document.getElementById('mensajeVacio');

  function filtrarProductos() {
    const termino = inputBuscar.value.toLowerCase();
    const categoria = filtro.value;
    let visibles = 0;

    productos.forEach(prod => {
      const nombre = prod.querySelector('h3').textContent.toLowerCase();
      const cat = prod.dataset.categoria;

      const coincideNombre = nombre.includes(termino);
      const coincideCategoria = !categoria || cat === categoria;

      if (coincideNombre && coincideCategoria) {
        prod.classList.remove("oculto");
        visibles++;
      } else {
        prod.classList.add("oculto");
      }
    });

    mensaje.style.display = visibles === 0 ? "block" : "none";
  }

  // Ejecutar la función cuando se escribe o cambia la categoría
  inputBuscar.addEventListener('input', filtrarProductos);
  filtro.addEventListener('change', filtrarProductos);
</script>


</body>
</html>
