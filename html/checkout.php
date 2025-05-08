<?php
require_once '../php/db.php';
include "../menu.php";

if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../php/login.php");
  exit;
}
$id_usuario = $_SESSION['usuario_id'];

// Obtener ítems del carrito con el stock disponible
$sql = "SELECT c.id, c.id_producto, p.nombre, p.valor, p.urlImagen, c.cantidad, p.existencia,
               (p.valor * c.cantidad) AS subtotal
        FROM carrito c
        JOIN products p ON c.id_producto = p.id
        WHERE c.id_usuario = ?";

// Verificar si la consulta se prepara correctamente
if (!$stmt = $conn->prepare($sql)) {
    die("Error en la consulta SQL: " . $conn->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
    $items[] = $row;
}

// Procesar peticiones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['action']) && $_POST['action'] === 'actualizar') {
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);

    // Verificar que la cantidad sea válida
    $stmt = $conn->prepare("SELECT existencia FROM products WHERE id = ?");
    $stmt->bind_param("i", $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    if ($cantidad > 0 && $cantidad <= $producto['existencia']) {
      $stmt = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?");
      $stmt->bind_param("iii", $cantidad, $id_usuario, $id_producto);
      $stmt->execute();
      echo 'ok';
    } else {
      echo 'error'; // Cantidad inválida
    }
    exit;
  }

  if (isset($_POST['action']) && $_POST['action'] === 'vaciar') {
    $stmt = $conn->prepare("DELETE FROM carrito WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    echo 'ok';
    exit;
  }
  

  if (isset($_POST['action']) && $_POST['action'] === 'eliminar') {
    $id_producto = intval($_POST['id_producto']);
    $stmt = $conn->prepare("DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?");
    $stmt->bind_param("ii", $id_usuario, $id_producto);
    $stmt->execute();
    echo 'ok';
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pagar') {
    $stmt = $conn->prepare("SELECT c.id_producto, c.cantidad, p.existencia 
                            FROM carrito c 
                            JOIN products p ON c.id_producto = p.id 
                            WHERE c.id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        if ($row['cantidad'] > $row['existencia']) {
            echo 'error';
            exit;
        }
    }

    // Procesar el pago aquí
    echo 'ok';
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Checkout | Distribuidora Lorenzo</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/checkout.css">
</head>
<body>

<main class="contenedor-checkout">
  <h2>Resumen de Compra</h2>

  <?php if (!empty($items)): ?>
    <table class="tabla-checkout">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Imagen</th>
          <th>Precio Unitario</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
          <th>Eliminar</th>
        </tr>
      </thead>
      <tbody id="tbody-carrito">
        <?php foreach ($items as $item): ?>
        <tr data-id="<?= $item['id_producto'] ?>" data-stock="<?= $item['existencia'] ?>">
          <td><?= htmlspecialchars($item['nombre']) ?></td>
          <td><img src="<?= htmlspecialchars($item['urlImagen']) ?>" alt=""></td>
          <td class="precio-unitario"><?= number_format($item['valor'], 2) ?></td>
          <td>
            <input type="number" class="input-cantidad" 
                   min="1" 
                   max="<?= $item['existencia'] ?>" 
                   data-id="<?= $item['id_producto'] ?>" 
                   data-stock="<?= $item['existencia'] ?>" 
                   value="<?= $item['cantidad'] ?>">
          </td>
          <td class="subtotal-item"><?= number_format($item['subtotal'], 2) ?> DOP</td>
          <td><button class="eliminar-item" data-id="<?= $item['id_producto'] ?>">✕</button></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="totales">
      <p><strong>Subtotal:</strong> <span id="subtotal">0</span> DOP</p>
      <p><strong>ITBIS (18%):</strong> <span id="itbis">0</span> DOP</p>
      <p><strong>Total:</strong> <span id="total">0</span> DOP</p>
    </div>

    <div id="paypal-button-container"></div>

  <?php else: ?>
    <p>Tu carrito está vacío. <a href="productos.php">Ver productos</a></p>
  <?php endif; ?>
</main>

<script src="https://www.paypal.com/sdk/js?client-id=AXUAVkqwfkvaeRrSB0AqHxi3aD8bVMnS6oOBucZteoPKfrSJ0FIKDuFlBGygkfqnNA5DRLl9ZBS902eo&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card"></script>
<script>
function calcularTotales() {
    let subtotal = 0;
    document.querySelectorAll('#tbody-carrito tr').forEach(row => {
        const precio = parseFloat(row.querySelector('.precio-unitario').textContent.replace(',', ''));
        const cantidad = parseInt(row.querySelector('.input-cantidad').value);
        const sub = precio * cantidad;
        subtotal += sub;
        row.querySelector('.subtotal-item').textContent = sub.toFixed(2) + ' DOP';
    });
    const itbis = subtotal * 0.18;
    const total = subtotal + itbis;

    document.getElementById('subtotal').textContent = subtotal.toFixed(2);
    document.getElementById('itbis').textContent = itbis.toFixed(2);
    document.getElementById('total').textContent = total.toFixed(2);
}

document.querySelectorAll('.input-cantidad').forEach(input => {
    input.addEventListener('change', e => {
        const id = e.target.dataset.id;
        const cantidad = parseInt(e.target.value);
        const stock = parseInt(e.target.dataset.stock);

        // Validar cantidad
        if (cantidad < 1) {
            alert('La cantidad no puede ser menor a 1.');
            e.target.value = 1;
            return;
        }

        if (cantidad > stock) {
            alert(`La cantidad no puede ser mayor al stock disponible (${stock}).`);
            e.target.value = stock;
            return;
        }

        // Actualizar cantidad en el servidor
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=actualizar&id_producto=${id}&cantidad=${cantidad}`
        }).then(res => res.text())
          .then(response => {
              if (response === 'error') {
                  alert('No se pudo actualizar la cantidad. Verifica el stock.');
                  e.target.value = stock; // Revertir al stock máximo
              } else {
                  calcularTotales();
              }
          });
    });
});

document.querySelectorAll('.eliminar-item').forEach(btn => {
  btn.addEventListener('click', e => {
    const id = e.target.dataset.id;
    fetch('', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=eliminar&id_producto=${id}`
    }).then(() => {
      e.target.closest('tr').remove();
      calcularTotales();
    });
  });
});

document.addEventListener('DOMContentLoaded', calcularTotales);

// Paypal
paypal.Buttons({
  createOrder: function(data, actions) {
    const totalDOP = parseFloat(document.getElementById('total').textContent);
    const usd = (totalDOP / 60).toFixed(2);
    return actions.order.create({
      purchase_units: [{
        amount: {
          value: usd,
          currency_code: "USD"
        }
      }]
    });
  },
  onApprove: function(data, actions) {
  return actions.order.capture().then(function(details) {
    // Vaciar carrito en la base de datos
    fetch('', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'action=vaciar'
    }).then(() => {
      window.location.href = '../php/gracias.php'; // Redirige a página de agradecimiento
    });
  }); 
  }

}).render('#paypal-button-container');
</script>
<div id="paypal-button-container"></div>
<script src="../js/checkout.js"></script>


</body>
</html>
