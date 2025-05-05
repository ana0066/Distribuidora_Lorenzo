<head>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/carrito.css">
    <link rel="stylesheet" href="../css/productos.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<header>
    <div class="principal-info">

        <div class="menu-toggle" id="menu-toggle">
            <i class="fas fa-bars"></i>
        </div>

        <div class="info-logo">
            <img src="../img/logo.png" alt="Logo de Distribuidora Lorenzo">
        </div>
        <div class="info-nombre">
            <h1>Distribuidora Lorenzo</h1>
        </div>
        <div class="info-telefono">
            <p><i class="fas fa-phone"></i> (809) 906-3559</p>
        </div>

    </div>

    <div class="navegador-info">
        <nav class="nav" id="nav">
            <ul class="nav-links">
                <li><a href="../html/index.php">Inicio</a></li>
                <li><a href="../html/nosotros.php">Nosotros</a></li>
                <li><a href="../html/productos.php">Productos</a></li>
                <li><a href="../html/contacto.php">Contacto</a></li>
                <?php 
                // Mostrar "Panel Admin" si el rol es admin o superadmin
                if (isset($_SESSION['rol']) && ($_SESSION['rol'] === 'admin' || $_SESSION['rol'] === 'superadmin')) {
                    echo "<li><a href='../admin/admin.php'>Administrar productos</a></li>";
                }

                // Mostrar "Administrar Usuarios" solo si el rol es superadmin
                if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'superadmin') {
                    echo "<li><a href='../html/admin_usuarios.php'>Administrar Usuarios</a></li>";
                }
                ?>
            </ul>
        </nav>

        <div class="icons">

            <div class="buscador-container">
                <input type="text" id="buscador" class="buscador" placeholder="Buscar...">
                <i class="fas fa-search"></i>
                <ul id="sugerencias" class="sugerencias"></ul>
            </div>

            <?php 
            if (isset($_SESSION['usuario'])) {
                echo "<a class='searchToggle logoutBtn' id='logoutBtn' href='#' onclick='confirmLogout(event)'><i class='fas fa-sign-out-alt'></i></a>";
                echo "<span class='username'>" . $_SESSION['usuario'] . "</span>";
            } else {
                echo "<a class='searchToggle' href='../php/login.php'><i class='fas fa-user'></i></a>";
            }
            ?>
            <i class="fas fa-shopping-cart" onclick="toggleCart()"></i>
            <span id="cart-count">0</span>
        </div>
    </div>
</header>

<div id="cartModal" class="cart-modal">
    <div class="cart-header">
        <h2>Tu Carrito</h2>
        <button class="close-cart" onclick="toggleCart()">×</button>
    </div>
    <div class="cart-items">
        <!-- Los productos se generarán dinámicamente con JavaScript -->
    </div>
    <div class="cart-footer">
        <p>Total: <span id="cart-total">$0.00</span></p>
        <button class="checkout-button" onclick="checkout()">Ir a Checkout</button>
    </div>
</div>

<script>
    const toggle = document.getElementById('menu-toggle');
    const nav = document.getElementById('nav');

    toggle.addEventListener('click', () => {
        nav.classList.toggle('active');
    });

    function confirmLogout(event) {
        event.preventDefault(); // Evita la redirección inmediata
        const confirmacion = confirm("¿Estás seguro de que deseas cerrar sesión?");
        if (confirmacion) {
            window.location.href = '../php/logout.php'; // Redirige al archivo de logout
        }
    }
</script>

<script src="../js/script.js"></script>
    <script src="../carrito/carrito.js"></script>
    <script src="../js/productos.js"></script>