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
  <h2>Productos</h2>
    <div id="productos">
        <?php while ($p = $resultado->fetch_assoc()): ?>
            <div>
                <img src="<?= $p['urlImagen'] ?>" width="100">
                <h4><?= $p['nombre'] ?></h4>
                <p>RD$ <?= $p['valor'] ?></p>
                <button onclick="agregarCarrito(<?= $p['id'] ?>)">Agregar al carrito</button>
            </div>
        <?php endwhile; ?>
    </div>
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
