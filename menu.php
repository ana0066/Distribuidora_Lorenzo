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
                    echo "<li><a href='../admin/admin.php'>Panel Admin</a></li>";
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