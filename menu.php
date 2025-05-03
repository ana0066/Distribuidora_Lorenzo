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
            <li><a href="#">Inicio</a></li>
            <li><a href="../html/nosotros.php">Nosotros</a></li>
            <li><a href="../html/productos.php">Productos</a></li>
            <li><a href="../html/contacto.php">Contacto</a></li>
            <?php 
            if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                echo "<li><a href='../admin/admin.php'>Panel Admin</a></li>";
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
            if (isset($_SESSION['usuario' || 'admin'])) {
                echo "<a class='searchToggle logoutBtn' id='logoutBtn' href='../php/logout.php'><i class='fas fa-sign-out-alt'></i></a>";
                echo "<span class='username'>" . $_SESSION['usuario' || 'admin'] . "</span>";
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
</script>