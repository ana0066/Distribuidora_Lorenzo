<?php
session_start();
include '../php/db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Debes iniciar sesión para agregar productos al carrito.'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados por la solicitud
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id_producto']) && isset($data['cantidad'])) {
        $idProducto = intval($data['id_producto']);
        $cantidad = intval($data['cantidad']);
        $usuarioId = $_SESSION['usuario_id'];

        // Validar que la cantidad sea mayor o igual a 1
        if ($cantidad < 1) {
            echo json_encode([
                'success' => false,
                'message' => 'La cantidad debe ser al menos 1.'
            ]);
            exit();
        }

        // Verificar si el producto existe y si hay stock suficiente
        $stmt = $conn->prepare("SELECT existencia, valor FROM products WHERE id = ?");
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $producto = $resultado->fetch_assoc();

            // Verificar si la cantidad solicitada es mayor que la existencia
            if ($cantidad > $producto['existencia']) {
                echo json_encode([
                    'success' => false,
                    'message' => 'La cantidad solicitada excede la existencia disponible.'
                ]);
                exit();
            }

            // Verificar si el producto ya está en el carrito
            $stmt = $conn->prepare("SELECT cantidad FROM carrito WHERE id_usuario = ? AND id_producto = ?");
            $stmt->bind_param("ii", $usuarioId, $idProducto);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Si el producto ya está en el carrito, actualizar la cantidad
                $carrito = $result->fetch_assoc();
                $nuevaCantidad = $carrito['cantidad'] + $cantidad;

                // Verificar que la nueva cantidad no exceda la existencia
                if ($nuevaCantidad > $producto['existencia']) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'La cantidad total en el carrito excede la existencia disponible.'
                    ]);
                    exit();
                }

                // Actualizar la cantidad en el carrito
                $stmt = $conn->prepare("UPDATE carrito SET cantidad = ? WHERE id_usuario = ? AND id_producto = ?");
                $stmt->bind_param("iii", $nuevaCantidad, $usuarioId, $idProducto);
                $stmt->execute();
            } else {
                // Si el producto no está en el carrito, agregarlo
                $stmt = $conn->prepare("INSERT INTO carrito (id_usuario, id_producto, cantidad, fecha_agregado) VALUES (?, ?, ?, NOW())");
                $stmt->bind_param("iii", $usuarioId, $idProducto, $cantidad);
                $stmt->execute();
            }

            echo json_encode([
                'success' => true,
                'message' => 'Producto agregado al carrito.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Producto no encontrado.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Datos incompletos.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
}
?>
