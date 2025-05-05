<?php
include "../menu.php";
require_once '../php/db.php';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../php/login.php");
    exit;
}
$id_usuario = $_SESSION['usuario_id'];


// Traer ítems del carrito
$stmt = $conn->prepare("
  SELECT c.id AS id_carrito, p.nombre, p.valor, p.urlImagen, c.cantidad,
         (p.valor * c.cantidad) AS subtotal
  FROM carrito c
  JOIN products p ON c.id_producto = p.id
  WHERE c.id_usuario = ?
");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res    = $stmt->get_result();
$items  = [];
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

  <?php if (count($items) > 0): ?>
    <table class="tabla-checkout">
      <thead>
        <tr>
          <th>Producto</th>
          <th>Imagen</th>
          <th>Precio U.</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
          <th>Eliminar</th>
        </tr>
      </thead>
      <tbody id="checkout-body">
        <?php foreach ($items as $it): ?>
        <tr data-id="<?= $it['id_carrito'] ?>">
          <td><?= htmlspecialchars($it['nombre']) ?></td>
          <td><img src="<?= htmlspecialchars($it['urlImagen']) ?>" alt="" ></td>
          <td class="unitario"><?= $it['valor'] ?></td>
          <td>
            <input type="number"
                   class="qty"
                   min="1"
                   value="<?= $it['cantidad'] ?>">
          </td>
          <td class="subtotal-cell"><?= $it['subtotal'] ?></td>
          <td>
            <button class="btn-eliminar">×</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="resumen">
      <p>Subtotal: <strong id="res-sub">$0.00</strong></p>
      <p>ITBIS (18%): <strong id="res-itbis">$0.00</strong></p>
      <p>Total: <strong id="res-total">$0.00</strong></p>
    </div>

    <div id="paypal-button-container"></div>
  <?php else: ?>
    <p>Tu carrito está vacío. <a href="productos.php">Ver productos</a></p>
  <?php endif; ?>
</main>

<!-- SDK de PayPal: reemplaza YOUR_CLIENT_ID por tu client-id -->
<script src="https://www.paypal.com/sdk/js?client-id=AXUAVkqwfkvaeRrSB0AqHxi3aD8bVMnS6oOBucZteoPKfrSJ0FIKDuFlBGygkfqnNA5DRLl9ZBS902eo&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo,paylater,card"></script>
<script>
// Variables globales
const rows = document.querySelectorAll('#checkout-body tr');
const resSub   = document.getElementById('res-sub');
const resItbis = document.getElementById('res-itbis');
const resTotal = document.getElementById('res-total');

// Función para recalcular totales
function recalcular() {
  let subtotal = 0;
  rows.forEach(row => {
    const unit   = parseFloat(row.querySelector('.unitario').textContent);
    const qtyInp = row.querySelector('.qty');
    const qty    = parseInt(qtyInp.value, 10);
    const sub    = unit * qty;
    row.querySelector('.subtotal-cell').textContent = sub.toFixed(2);
    subtotal += sub;
  });
  const itbis = subtotal * 0.18;
  const total = subtotal + itbis;
  resSub.textContent   = '$' + subtotal.toFixed(2);
  resItbis.textContent = '$' + itbis.toFixed(2);
  resTotal.textContent = '$' + total.toFixed(2);
}

// Llamar al cargar
recalcular();

// Bind evento cambio cantidad
rows.forEach(row => {
  const qty = row.querySelector('.qty');
  qty.addEventListener('change', e => {
    const newQty = parseInt(e.target.value, 10);
    const idc    = row.dataset.id;
    // AJAX para actualizar en BD
    fetch('../carrito/actualizar_carrito.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({ id_carrito: idc, cantidad: newQty })
    }).then(r => r.json())
      .then(resp => {
        if (resp.ok) recalcular();
      });
  });

  // Botón eliminar
  row.querySelector('.btn-eliminar').addEventListener('click', () => {
    const idc = row.dataset.id;
    fetch('../carrito/eliminar_carrito.php?id=' + idc)
      .then(r => r.json())
      .then(resp => {
        if (resp.ok) {
          row.remove();
          recalcular();
        }
      });
  });
});

// Configurar PayPal
paypal.Buttons({
  style: { layout: 'vertical', color: 'gold', shape: 'rect', label: 'paypal' },
  createOrder: (data, actions) => {
    // Usamos el total ya calculado
    const monto = parseFloat(resTotal.textContent.replace('$',''));
    return actions.order.create({
      purchase_units: [{ amount: { value: monto.toFixed(2) } }]
    });
  },
  onApprove: (data, actions) => {
    return actions.order.capture().then(details => {
      // Una vez pagado, redirigimos a tu endpoint para registrar
      window.location.href = "../carrito/pago.php";
    });
  },
  onError: err => {
    alert('Error con PayPal: ' + err);
  }
}).render('#paypal-button-container');
</script>

</body>
</html>
