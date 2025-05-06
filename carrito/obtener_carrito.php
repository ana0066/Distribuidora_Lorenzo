<?php
session_start();
require_once '../php/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
  echo json_encode(['items' => []]);
  exit;
}

$id_usuario = $_SESSION['usuario_id'];
$sql = "SELECT c.id AS id_carrito, p.nombre, p.valor, p.urlImagen,
               c.cantidad, (p.valor * c.cantidad) AS subtotal
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
echo json_encode(['items' => $items]);
