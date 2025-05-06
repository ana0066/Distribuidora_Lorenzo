<?php
session_start();
require_once '../php/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
  echo json_encode(['error' => 'Usuario no autenticado', 'items' => []]);
  exit;
}

$id_usuario = $_SESSION['usuario_id'];
$sql = "SELECT c.id AS id_carrito, p.nombre, p.valor, p.urlImagen,
               c.cantidad, (p.valor * c.cantidad) AS subtotal
        FROM carrito c
        JOIN products p ON c.id_producto = p.id
        WHERE c.id_usuario = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  echo json_encode(['error' => 'Error en la preparación de la consulta']);
  exit;
}

$stmt->bind_param("i", $id_usuario);
if (!$stmt->execute()) {
  echo json_encode(['error' => 'Error al ejecutar la consulta']);
  exit;
}

$res = $stmt->get_result();
if ($res->num_rows === 0) {
  echo json_encode(['error' => 'No hay productos en el carrito', 'items' => []]);
  exit;
}

$items = [];
while ($row = $res->fetch_assoc()) {
  $items[] = $row;
}
echo json_encode(['items' => $items]);
