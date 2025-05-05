<?php
session_start();
include '../php/db.php';

// Verificar que sea superadmin
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'superadmin') {
    header('Location: ../html/index.php');
    exit;
}

// Variables de estado
$errors = [];
$success = '';

// Manejo de acciones: Create, Update, Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $rol = $_POST['rol'];

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
                $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, contraseña, rol, token, verificado) VALUES (?, ?, ?, ?, ?, 1)");
                $stmt->bind_param("sssss", $nombre, $email, $passwordHash, $rol, $token);
                if ($stmt->execute()) {
                    $success = "Usuario creado correctamente.";
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
            if (!empty($_POST['password'])) {
                $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=?, contraseña=?, rol=? WHERE id=?");
                $stmt->bind_param("ssssi", $nombre, $email, $passwordHash, $rol, $id);
            } else {
                $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, email=?, rol=? WHERE id=?");
                $stmt->bind_param("sssi", $nombre, $email, $rol, $id);
            }
            if ($stmt->execute()) {
                $success = "Usuario actualizado correctamente.";
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
    $stmt = $conn->prepare("SELECT id, nombre, email, rol FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $editUser = $result->fetch_assoc();
}

// Consultar todos los usuarios
$users = [];
$result = $conn->query("SELECT id, nombre, email, rol FROM usuarios");
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
</head>
<body>

<?php include '../menu.php';?>

<?php
// Generar alertas emergentes
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
        <input
            type="text"
            id="user-search"
            placeholder="Buscar usuarios por cualquier campo..."
            class="user-search-input" /> 
    </div>

    <!-- Crear / Editar Usuario -->
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
                <?php foreach (['usuario','admin','superadmin'] as $roleOption): ?>
                    <option value="<?php echo $roleOption; ?>" <?php echo ($editMode && $editUser['rol']==$roleOption) ? 'selected' : ''; ?>><?php echo ucfirst($roleOption); ?></option>
                <?php endforeach; ?>
            </select>
            <label><?php echo $editMode ? 'Nueva contraseña (opcional)' : 'Contraseña'; ?>:</label>
            <input type="password" name="password" <?php echo $editMode ? '' : 'required'; ?>>
            <button type="submit"><?php echo $editMode ? 'Actualizar Usuario' : 'Crear Usuario'; ?></button>
        </form>
    </div>

    <!-- Gestionar Usuarios -->
    <div id="content-manage" class="tab-content">
        <table>
            <thead>
                <tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo $user['rol']; ?></td>
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
