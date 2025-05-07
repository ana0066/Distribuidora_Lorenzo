<?php
session_start();
include '../php/db.php';

$idCarrito = $_POST['id'];
$cantidad = $_POST['cantidad'];

if (!is_numeric($cantidad) || $cantidad <= 0) {
    exit;
}

// Validar stock
$carrito = $conn->query("SELECT id_producto FROM carrito WHERE id = $idCarrito")->fetch_assoc();
$producto = $conn->query("SELECT existencia FROM products WHERE id = {$carrito['id_producto']}")->fetch_assoc();

if ($cantidad > $producto['existencia']) {
    echo "Cantidad mayor al stock disponible.";
    exit;
}

$conn->query("UPDATE carrito SET cantidad = $cantidad WHERE id = $idCarrito");
