<?php
session_start();
include '../php/db.php';

$id = $_POST['id'];
$conn->query("DELETE FROM carrito WHERE id = $id");
