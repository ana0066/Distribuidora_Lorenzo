<?php
session_start();
require_once '../php/db.php';

$id_usuario  = $_SESSION['id_usuario'] ?? 0;
$id_producto = $_POST['id_producto'] ?? 0;
$cantidad    = $_POST['cantidad'] ?? 1;

if ($id_usuario && $id_producto && $cantidad > 0) {
    $sql = "UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $cantidad, $id_usuario, $id_producto);
    $stmt->execute();
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>
