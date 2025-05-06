<?php
session_start();
require_once '../php/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
  echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
  exit;
}

if (!isset($_GET['id'])) {
  echo json_encode(['success' => false, 'error' => 'ID del producto no especificado']);
  exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_carrito = intval($_GET['id']);

$sql = "DELETE FROM carrito WHERE id = ? AND id_usuario = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
  echo json_encode(['success' => false, 'error' => 'Error en la preparación de la consulta']);
  exit;
}

$stmt->bind_param("ii", $id_carrito, $id_usuario);

if ($stmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto']);
}