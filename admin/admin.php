<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../menu.php";

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Panel Admin — Productos</title>
  <link rel="stylesheet" href="../admin/style.css">
</head>
<body>
  <main class="admin-container">
    <h1>Administración de Productos</h1>
    <div class="tabs">
      <button id="tab-add" class="active">Añadir</button>
      <button id="tab-edit">Editar</button>
      <button id="tab-delete">Eliminar</button>
      <button id="tab-list">Listar</button>
    </div>

    <!-- AÑADIR -->
    <section id="panel-add" class="panel active">
      <h2>Añadir Producto</h2>
      <form id="form-add" enctype="multipart/form-data">
        <input name="nombre" type="text" placeholder="Nombre del producto" required>
        <input name="valor" type="number" step="0.01" placeholder="Valor (DOP)" required>
        <input name="existencia" type="number" placeholder="Existencia" required>

        <!-- Imagen: upload *o* URL -->
        <label>Subir imagen:</label>
        <input name="imagenFile" type="file" accept="image/*">
        <label>O URL de imagen:</label>
        <input name="imagenURL" type="url" placeholder="https://...">

        <select name="categoria" required>
          <option value="">Selecciona categoría…</option>
          <option value="mobiliaria">Mobiliaria</option>
          <option value="vajilla">Vajilla</option>
          <option value="decoraciones">Decoraciones</option>
          <option value="herramientas">Herramientas</option>
          <option value="electrodomesticos">Electrodomésticos</option>
        </select>

        <button type="submit" class="btn">Añadir</button>
      </form>
    </section>

    <!-- EDITAR -->
    <section id="panel-edit" class="panel">
      <h2>Editar Producto</h2>
      <form id="form-edit">
        <select name="id" required>
          <option value="">Selecciona un producto…</option>
        </select>
        <select name="atributo" required>
          <option value="">Qué editar…</option>
          <option value="nombre">Nombre</option>
          <option value="valor">Valor</option>
          <option value="existencia">Existencia</option>
          <option value="urlImagen">URL Imagen</option>
          <option value="categoria">Categoría</option>
        </select>
        <input name="nuevoValor" type="text" placeholder="Nuevo valor" required>
        <button type="submit" class="btn">Guardar cambios</button>
      </form>
    </section>

    <!-- ELIMINAR -->
    <section id="panel-delete" class="panel">
      <h2>Eliminar Producto</h2>
      <form id="form-delete">
        <select name="id" required>
          <option value="">Selecciona un producto…</option>
        </select>
        <button type="submit" class="btn btn-danger">Eliminar</button>
      </form>
    </section>

    <!-- LISTAR -->
    <section id="panel-list" class="panel">
      <h2>Listado de Productos</h2>

      <!-- BUSCADOR -->
      <div class="search-container">
        <input
          type="text"
          id="searchProduct"
          placeholder="Buscar productos por nombre, categoría…"
          class="search-input"
        />
      </div>

      <button id="btn-cargar-productos" class="btn">Actualizar listado</button>


      <div id="product-list" class="grid-products">
        <!-- se inyectan tarjetas aquí -->
      </div>
    </section>
  </main>

  <script src="../admin/admin.js"></script>
</body>
</html>
