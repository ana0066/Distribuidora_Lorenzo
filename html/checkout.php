<?php
require_once '../php/db.php';

include "../menu.php";

if (!isset($_SESSION['usuario_id'])) {
  header("Location: ../php/login.php");
  exit;
}
$id_usuario = $_SESSION['usuario_id'] ?? 1;

// Obtener ítems del carrito
$sql = "SELECT c.id, c.id_producto, p.nombre, p.valor, p.urlImagen, c.cantidad, 
               (p.valor * c.cantidad) AS subtotal
        FROM carrito c
        JOIN products p ON c.id_producto = p.id
        WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();

$items = [];
while ($row = $res->fetch_assoc()) {
    $items[] = $row;
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
        <tr data-id="<?= $item['id_producto'] ?>">
          <td><?= htmlspecialchars($item['nombre']) ?></td>
          <td><img src="<?= htmlspecialchars($item['urlImagen']) ?>" alt=""></td>
          <td class="precio-unitario"><?= number_format($item['valor'], 2) ?></td>
          <td>
            <input type="number" class="input-cantidad" min="1"
                   data-id="<?= $item['id_producto'] ?>"
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
// Calcular totales
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

// Actualizar cantidad
document.querySelectorAll('.input-cantidad').forEach(input => {
  input.addEventListener('change', e => {
    const id = e.target.dataset.id;
    const cantidad = e.target.value;
    fetch('../php/actualizar_cantidad.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id_producto=${id}&cantidad=${cantidad}`
    }).then(() => calcularTotales());
  });
});

// Eliminar producto
document.querySelectorAll('.eliminar-item').forEach(btn => {
  btn.addEventListener('click', e => {
    const id = e.target.dataset.id;
    fetch('../php/eliminar_item.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `id_producto=${id}`
    }).then(() => {
      e.target.closest('tr').remove();
      calcularTotales();
    });
  });
});

document.addEventListener('DOMContentLoaded', calcularTotales);

// Paypal (convierte DOP a USD suponiendo tasa 60)
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
      alert('Pago realizado por ' + details.payer.name.given_name);
      // Aquí puedes vaciar el carrito o redirigir
    });
  }
}).render('#paypal-button-container');
</script>

</body>
</html>
