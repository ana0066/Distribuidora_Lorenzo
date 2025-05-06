<?php
session_start();
require_once '../php/db.php';

$id_usuario  = $_SESSION['id_usuario'] ?? 0;
$id_producto = $_POST['id_producto'] ?? 0;

if ($id_usuario && $id_producto) {
    $sql  = "DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_usuario, $id_producto);
    $stmt->execute();
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Faltan datos"]);
}
?>
