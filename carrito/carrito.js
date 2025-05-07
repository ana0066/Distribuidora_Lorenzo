document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cartIcon');
    const asideCarrito = document.getElementById('asideCarrito');
    const btnCerrarCarrito = document.getElementById('btnCerrarCarrito');
    const cartCount = document.getElementById('cart-count');
    const carritoItems = document.querySelector('.carrito-items');
    const carritoTotal = document.getElementById('carrito-total');

    // Abrir carrito
    cartIcon.addEventListener('click', () => {
        asideCarrito.classList.add('mostrar');
        loadCart(); // Cargar el carrito al abrir
    });

    // Cerrar carrito
    btnCerrarCarrito.addEventListener('click', () => {
        asideCarrito.classList.remove('mostrar');
    });

    // Función para cargar el carrito desde el servidor
    function loadCart() {
        fetch('../carrito/obtener.php')
            .then(res => res.json())
            .then(data => {
                // Actualizar el contenido del carrito
                carritoItems.innerHTML = data.html || '<p>Tu carrito está vacío.</p>';
                carritoTotal.textContent = `${data.total.toFixed(2)} DOP`;
                cartCount.textContent = data.cantidad || 0;
            })
            .catch(err => console.error('Error cargando el carrito:', err));
    }

    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        fetch('../carrito/obtener.php')
            .then(res => res.text()) // Cambia a .text() temporalmente
            .then(data => {
                console.log('Respuesta del servidor:', data); // Verifica la respuesta completa
                const jsonData = JSON.parse(data); // Intenta convertir a JSON
                cartCount.textContent = jsonData.cantidad || 0; // Actualiza el contador
            })
            .catch(err => console.error('Error actualizando el contador del carrito:', err));
    }

    // Función para agregar un producto al carrito
    window.agregarCarrito = function (idProducto) {
        fetch('../carrito/agregar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_producto: idProducto, cantidad: 1 })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    loadCart(); // Recargar el carrito
                    actualizarContadorCarrito(); // Actualizar el contador
                } else {
                    alert('Error al agregar al carrito');
                }
            })
            .catch(err => console.error('Error:', err));
    };

    // Función para eliminar un producto del carrito
    window.eliminarDelCarrito = function (idProducto) {
        fetch('../carrito/eliminar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_producto: idProducto })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    loadCart(); // Recargar el carrito
                    actualizarContadorCarrito(); // Actualizar el contador
                } else {
                    alert('Error al eliminar del carrito');
                }
            })
            .catch(err => console.error('Error:', err));
    };

    // Función para actualizar la cantidad de un producto en el carrito
    window.actualizarCantidad = function (idProducto, nuevaCantidad) {
        fetch('../carrito/actualizar.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_producto: idProducto, cantidad: nuevaCantidad })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    loadCart(); // Recargar el carrito
                    actualizarContadorCarrito(); // Actualizar el contador
                } else {
                    alert('Error al actualizar la cantidad');
                }
            })
            .catch(err => console.error('Error:', err));
    };

    // Inicializar el carrito y el contador al cargar la página
    loadCart();
    actualizarContadorCarrito();
});
