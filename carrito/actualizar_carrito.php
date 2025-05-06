<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../php/login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

if (!empty($_POST['cantidades']) && is_array($_POST['cantidades'])) {
    foreach ($_POST['cantidades'] as $id_carrito => $cantidad) {
        $cantidad = max(1, (int)$cantidad);
        $sql      = "UPDATE carrito 
                     SET cantidad = ? 
                     WHERE id = ? AND id_usuario = ?";
        $stmt     = $conn->prepare($sql);
        $stmt->bind_param("iii", $cantidad, $id_carrito, $id_usuario);
        $stmt->execute();
    }
}

header("Location: ../carrito/carrito.php");
exit;
