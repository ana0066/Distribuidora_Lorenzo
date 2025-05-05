<?php
include "../php/db.php";

$nombre = $_POST['nombre'] ?? '';
$valor = $_POST['valor'] ?? 0;
$existencia = $_POST['existencia'] ?? 0;
$categoria = $_POST['categoria'] ?? '';
$urlImagen = '';

if (isset($_FILES['imagenFile']) && $_FILES['imagenFile']['error'] === 0) {
    $nombreArchivo = time() . "_" . $_FILES['imagenFile']['name'];
    $rutaDestino = "../uploads/" . $nombreArchivo;
    move_uploaded_file($_FILES['imagenFile']['tmp_name'], $rutaDestino);
    $urlImagen = $rutaDestino;
} elseif (!empty($_POST['imagenURL'])) {
    $urlImagen = $_POST['imagenURL'];
}

$sql = "INSERT INTO products (nombre, valor, existencia, urlImagen, categoria) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sddss", $nombre, $valor, $existencia, $urlImagen, $categoria);
$success = $stmt->execute();

echo json_encode(["success" => $success]);
?>
