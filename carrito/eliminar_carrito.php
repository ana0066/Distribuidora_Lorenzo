<?php
session_start();
require_once '../php/db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../php/login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

if (isset($_GET['id'])) {
    $id_carrito = (int)$_GET['id'];
    $sql        = "DELETE FROM carrito WHERE id = ? AND id_usuario = ?";
    $stmt       = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_carrito, $id_usuario);
    $stmt->execute();
}

header("Location: ../carrito/carrito.php");
exit;
