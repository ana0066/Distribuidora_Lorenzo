<?php

include "../menu.php";

include '../php/db.php';
include '../php/enviar_correo.php'; // Asegúrate de tener esta función

// Verificar que sea superadmin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'superadmin') {
    header('Location: ../html/admin_usuarios.php');
    exit;
}

$errors = [];
$success = '';

// Reenviar token
if (isset($_GET['action']) && $_GET['action'] === 'reenviar_token' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT email, nombre, token FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if (enviarCorreoVerificacion($user['email'], $user['nombre'], $user['token'])) {
            $success = "Token reenviado correctamente.";
        } else {
            $errors[] = "No se pudo enviar el correo. Verifica la configuración.";
        }
    } else {
        $errors[] = "Usuario no encontrado.";
    }
}

// Verificar manualmente
if (isset($_GET['action']) && $_GET['action'] === 'verificar_manual' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("UPDATE usuarios SET verificado = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "Usuario marcado como verificado.";
    } else {
        $errors[] = "Error al actualizar verificación.";
    }
}

// Crear/Editar/Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $rol = isset($_POST['rol']) ? $_POST['rol'] : '';
    $marcarVerificado = isset($_POST['marcar_verificado']) ? true : false;

    if ($_POST['form_type'] === 'create') {
        if (empty($nombre) || empty($email) || empty($_POST['password'])) {
            $errors[] = "Todos los campos son requeridos.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email no válido.";
        } else {
            $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();
            if ($check->num_rows > 0) {
                $errors[] = "Ya existe un usuario con ese correo.";
            } else {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $token = bin2hex(random_bytes(16));
                $verificado = $marcarVerificado ? 1 : 0;

                $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol, token, verificado) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssi", $nombre, $email, $passwordHash, $rol, $token, $verificado);
                if ($stmt->execute()) {
                    if (!$marcarVerificado) {
                        enviarCorreoVerificacion($email, $nombre, $token);
                        $success = "Usuario creado correctamente. Se envió el correo de verificación.";
                    } else {
                        $success = "Usuario creado y marcado como verificado.";
                    }
                } else {
                    $errors[] = "Error al crear usuario.";
                }
            }
        }
    } elseif ($_POST['form_type'] === 'update') {
        $id = intval($_POST['user_id']);
        if (empty($nombre) || empty($email)) {
            $errors[] = "Nombre y email son requeridos.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email no válido.";
        } else {
            $verificado = $marcarVerificado ? 1 : 0;

            if (!empty($_POST['password'])) {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=?, contraseña=?, rol=?, verificado=? WHERE id=?");
                $stmt->bind_param("ssssii", $nombre, $email, $passwordHash, $rol, $verificado, $id);
            } else {
                $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=?, rol=?, verificado=? WHERE id=?");
                $stmt->bind_param("sssii", $nombre, $email, $rol, $verificado, $id);
            }
            if ($stmt->execute()) {
                if (!$marcarVerificado) {
                    enviarCorreoVerificacion($email, $nombre, $token);
                    $success = "Usuario actualizado correctamente. Se envió el correo de verificación.";
                } else {
                    $success = "Usuario actualizado y marcado como verificado.";
                }
            } else {
                $errors[] = "Error al actualizar usuario.";
            }
        }
    }
} elseif (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "Usuario eliminado correctamente.";
    } else {
        $errors[] = "Error al eliminar usuario.";
    }
}

// Si es editar, obtener datos
$editMode = false;
$editUser = [];
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $editMode = true;
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT id, nombre, email, rol, verificado FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editUser = $result->fetch_assoc();
}

// Consultar todos los usuarios
$users = [];
$result = $conn->query("SELECT id, nombre, email, rol, verificado FROM usuarios");
if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Usuarios</title>
    <link rel="stylesheet" href="../css/admin_usuarios.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        
    </style>
</head>
<body>


<?php
if ($success) {
    echo "<script>alert('" . addslashes($success) . "');</script>";
}
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<script>alert('" . addslashes($error) . "');</script>";
    }
}
?>

<div class="container">
    <h1>Administrar Usuarios</h1>

    <div class="tabs">
        <button id="tab-create" class="tab-btn active">Crear / Editar Usuario</button>
        <button id="tab-manage" class="tab-btn">Gestionar Usuarios</button>
    </div>

    <div class="search-container">
        <input type="text" id="user-search" placeholder="Buscar usuarios por cualquier campo..." class="user-search-input" />
    </div>

    <div id="content-create" class="tab-content active">
        <form method="POST" action="admin_usuarios.php">
            <input type="hidden" name="form_type" value="<?php echo $editMode ? 'update' : 'create'; ?>">
            <?php if ($editMode): ?>
                <input type="hidden" name="user_id" value="<?php echo $editUser['id']; ?>">
            <?php endif; ?>
            <label>Nombre completo:</label>
            <input type="text" name="nombre" value="<?php echo $editMode ? htmlspecialchars($editUser['nombre']) : ''; ?>" required>
            <label>Correo electrónico:</label>
            <input type="email" name="email" value="<?php echo $editMode ? htmlspecialchars($editUser['email']) : ''; ?>" required>
            <label>Rol:</label>
            <select name="rol">
                <?php foreach (['usuario', 'admin', 'superadmin'] as $roleOption): ?>
                    <option value="<?php echo $roleOption; ?>" <?php echo ($editMode && $editUser['rol'] == $roleOption) ? 'selected' : ''; ?>><?php echo ucfirst($roleOption); ?></option>
                <?php endforeach; ?>
            </select>
            <label><?php echo $editMode ? 'Nueva contraseña (opcional)' : 'Contraseña'; ?>:</label>
            <input type="password" name="password" <?php echo $editMode ? '' : 'required'; ?>>

            <!-- Botón para marcar como verificado -->
            <div class="extra-actions">
                <label>
                    <input type="checkbox" name="marcar_verificado" id="marcarVerificado">
                    Marcar como verificado
                </label>
            </div>

            <button type="submit"><?php echo $editMode ? 'Actualizar Usuario' : 'Crear Usuario'; ?></button>
        </form>
    </div>

    <div id="content-manage" class="tab-content">
        <table>
        <thead>
            <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Verificado</th>
            <th>Acciones</th>
            </tr>
        </thead>

            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['rol']; ?></td>
                        <td><?php echo $user['verificado'] ? '✔️ Sí' : '❌ No'; ?></td>
                        <td class="actions">
                            <a href="admin_usuarios.php?action=edit&id=<?php echo $user['id']; ?>">Editar</a>
                            <a href="admin_usuarios.php?action=delete&id=<?php echo $user['id']; ?>" onclick="return confirm('¿Eliminar usuario?');">Eliminar</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../js/admin_usuarios.js"></script>
</body>
</html>
