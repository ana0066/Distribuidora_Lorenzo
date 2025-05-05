<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../php/login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;

if ($id_producto > 0) {
    // Verificar si ya está en el carrito
    $query = "SELECT * FROM carrito WHERE id_usuario = ? AND id_producto = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_usuario, $id_producto);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Ya está en el carrito → actualizar cantidad
        $query = "UPDATE carrito SET cantidad = cantidad + 1 WHERE id_usuario = ? AND id_producto = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id_usuario, $id_producto);
        $stmt->execute();
    } else {
        // No está en el carrito → insertarlo
        $query = "INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $id_usuario, $id_producto);
        $stmt->execute();
    }
}

header("Location: ../html/productos.php");
exit;
?>
