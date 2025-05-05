<?php
include "../php/db.php";

$id = $_POST['id'] ?? '';
$atributo = $_POST['atributo'] ?? '';
$nuevoValor = $_POST['nuevoValor'] ?? '';

$permitidos = ['nombre', 'valor', 'existencia', 'urlImagen', 'categoria'];
if (!in_array($atributo, $permitidos)) {
    echo json_encode(["success" => false, "error" => "Campo no permitido."]);
    exit;
}

$sql = "UPDATE products SET $atributo = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if (in_array($atributo, ['valor', 'existencia'])) {
    $stmt->bind_param("di", $nuevoValor, $id);
} else {
    $stmt->bind_param("si", $nuevoValor, $id);
}

$success = $stmt->execute();
echo json_encode(["success" => $success]);
?>
