<?php
session_start();
include '../php/db.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['total' => 0]);
    exit;
}

$id_usuario = $_SESSION['id'];

$stmt = $conn->prepare("SELECT SUM(cantidad) AS total FROM carrito WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$total = $result->fetch_assoc()['total'] ?? 0;

echo json_encode(['total' => $total]);
exit;
?>
