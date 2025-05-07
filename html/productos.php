<?php

include "../menu.php";
include_once '../php/db.php';


$stmt = $conn->prepare("SELECT * FROM products WHERE existencia > 0");
$stmt->execute();
$resultado = $stmt->get_result();

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
    <h2 class="titulo-seccion">Catálogo de Productos</h2>

    <div class="filtros-productos">
      <div class="filtro">
        <label for="filtroCategoria">Categoría</label>
        <select id="filtroCategoria">
          <option value="">Todas</option>
          <option value="mobiliaria">Mobiliaria</option>
          <option value="vajilla">Vajilla</option>
          <option value="decoraciones">Decoraciones</option>
          <option value="herramientas">Herramientas</option>
          <option value="electrodomesticos">Electrodomésticos</option>
        </select>
      </div>

      <div class="filtro">
        <label for="inputBuscar">Buscar</label>
        <input type="text" id="inputBuscar" placeholder="Ej. cama, vajilla...">
      </div>
    </div>

    <div id="productos" class="grid-productos">
      <?php while ($p = $resultado->fetch_assoc()): ?>
          <div class="card-producto" data-categoria="<?= $p['categoria'] ?>">
              <img src="<?= $p['urlImagen'] ?>" alt="<?= $p['nombre'] ?>">
              <h3><?= $p['nombre'] ?></h3>
              <p class="precio">RD$ <?= number_format($p['valor'], 2) ?></p>
              <p class="stock"><?= $p['existencia'] ?> disponibles</p>
              <button onclick="agregarCarrito(<?= $p['id'] ?>)">Agregar al carrito</button>
          </div>
      <?php endwhile; ?>
    </div>

    <p id="mensajeVacio" class="mensaje-vacio">No se encontraron productos.</p>
  </section>
</main>

<script>

  function agregarCarrito(idProducto) {
      fetch('../carrito/agregar.php', {
          method: 'POST',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          body: 'id_producto=' + idProducto
      })
      .then(res => res.text())
      .then(data => alert(data));
  }

  const inputBuscar = document.getElementById('inputBuscar');
  const filtro = document.getElementById('filtroCategoria');
  const productos = document.querySelectorAll('.card-producto');
  const mensaje = document.getElementById('mensajeVacio');

  function filtrarProductos() {
    const termino = inputBuscar.value.toLowerCase(); // Término de búsqueda
    const categoria = filtro.value; // Categoría seleccionada
    let visibles = 0; // Contador de productos visibles

    productos.forEach(prod => {
        const nombre = prod.querySelector('h3').textContent.toLowerCase(); // Nombre del producto
        const cat = prod.dataset.categoria; // Categoría del producto

        // Verificar si el producto coincide con el término de búsqueda y la categoría
        const coincideNombre = nombre.includes(termino);
        const coincideCategoria = !categoria || cat === categoria;

        if (coincideNombre && coincideCategoria) {
            prod.classList.remove("oculto"); // Mostrar producto
            visibles++;
        } else {
            prod.classList.add("oculto"); // Ocultar producto
        }
    });

    // Mostrar mensaje si no hay productos visibles
    mensaje.style.display = visibles === 0 ? "block" : "none";
  }

  // Ejecutar la función cuando se escribe en el buscador o se cambia la categoría
  inputBuscar.addEventListener('input', filtrarProductos);
  filtro.addEventListener('change', filtrarProductos);
</script>

<script src="../js/script.js"></script>

</body>
</html>
