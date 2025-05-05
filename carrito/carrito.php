<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../php/db.php';

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verificar si el usuario existe
    $stmt = $conn->prepare("SELECT id, nombre, email, contraseña, verificado FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verificar contraseña
        if (password_verify($password, $user['contraseña'])) {
            if ($user['verificado'] == 1) {
                // Guardar el ID del usuario en la sesión
                $_SESSION['id'] = $user['id'];
                $_SESSION['nombre'] = $user['nombre'];
                echo json_encode(['success' => true, 'message' => 'Inicio de sesión exitoso']);
                exit;
            } else {
                echo json_encode(['error' => 'Tu cuenta no ha sido verificada.']);
                exit;
            }
        } else {
            echo json_encode(['error' => 'Contraseña incorrecta.']);
            exit;
        }
    } else {
        echo json_encode(['error' => 'No se encontró una cuenta con ese correo.']);
        exit;
    }
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'No autenticado. Por favor, inicia sesión.']);
    exit;
}

$usuario_id = $_SESSION['id'];

// Validar entrada
$accion = $_POST['accion'] ?? '';
$id_producto = isset($_POST['id_producto']) ? (int)$_POST['id_producto'] : null;
$cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 1;

if (!in_array($accion, ['agregar', 'eliminar', 'obtener'])) {
    echo json_encode(['error' => 'Acción no válida']);
    exit;
}

if ($accion !== 'obtener' && (!$id_producto || $cantidad < 1)) {
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

// Manejar acciones
switch ($accion) {
    case 'agregar':
        $stmt = $conn->prepare("SELECT id FROM carrito WHERE id_usuario = ? AND id_producto = ?");
        $stmt->bind_param("ii", $usuario_id, $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $stmt = $conn->prepare("UPDATE carrito SET cantidad = cantidad + ? WHERE id_usuario = ? AND id_producto = ?");
            $stmt->bind_param("iii", $cantidad, $usuario_id, $id_producto);
        } else {
            $stmt = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $usuario_id, $id_producto, $cantidad);
        }

        if (!$stmt->execute()) {
            echo json_encode(['error' => 'Error al agregar al carrito', 'detalle' => $stmt->error]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito']);
        break;

    case 'eliminar':
        $stmt = $conn->prepare("DELETE FROM carrito WHERE id_usuario = ? AND id_producto = ?");
        $stmt->bind_param("ii", $usuario_id, $id_producto);

        if (!$stmt->execute()) {
            echo json_encode(['error' => 'Error al eliminar del carrito', 'detalle' => $stmt->error]);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito']);
        break;

    case 'obtener':
        $stmt = $conn->prepare("SELECT c.id AS carrito_id, c.cantidad, p.id AS producto_id, p.nombre, p.valor, p.urlImagen 
                                FROM carrito c 
                                JOIN products p ON c.id_producto = p.id 
                                WHERE c.id_usuario = ?");
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $carrito = [];

        while ($row = $result->fetch_assoc()) {
            $carrito[] = $row;
        }

        echo json_encode(['success' => true, 'data' => $carrito]);
        break;

    default:
        echo json_encode(['error' => 'Acción no reconocida']);
        break;
}
?>
