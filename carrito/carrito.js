document.addEventListener('DOMContentLoaded', () => {
    const botonesAgregar = document.querySelectorAll('.btn-agregar-carrito');
    const cartCount = document.getElementById('cart-count');
    const cartIcon = document.getElementById('cartIcon');
    const aside = document.getElementById('asideCarrito');
    const btnClose = document.getElementById('btnCerrarCarrito'); // Botón para cerrar el carrito
    const carritoItems = document.querySelector('.carrito-items'); // Contenedor de los ítems del carrito
    const carritoTotal = document.getElementById('carrito-total');

    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        fetch('../carrito/carrito_total.php')
            .then(res => res.json())
            .then(data => {
                if (cartCount) cartCount.textContent = data.total || 0;
            })
            .catch(err => console.error('Error actualizando contador:', err));
    }

    // Función para cargar los ítems del carrito
    function cargarCarrito() {
        fetch('../carrito/obtener_carrito.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    carritoItems.innerHTML = '<p>No hay productos en el carrito.</p>';
                    carritoTotal.textContent = '0 DOP';
                    cartCount.textContent = '0';
                    return;
                }

                carritoItems.innerHTML = '';
                let total = 0;
                let count = 0;

                data.items.forEach(item => {
                    total += item.subtotal;
                    count += item.cantidad;

                    const itemHTML = `
                        <div class="carrito-item" data-id="${item.id_carrito}">
                            <img src="${item.urlImagen}" alt="${item.nombre}" class="carrito-item-img">
                            <div class="carrito-item-info">
                                <h4>${item.nombre}</h4>
                                <p>Precio: $${item.valor}</p>
                                <p>Cantidad: ${item.cantidad}</p>
                                <p>Subtotal: $${item.subtotal}</p>
                                <button class="btn-eliminar" data-id="${item.id_carrito}">Eliminar</button>
                            </div>
                        </div>
                    `;
                    carritoItems.innerHTML += itemHTML;
                });

                carritoTotal.textContent = `${total} DOP`;
                cartCount.textContent = count;

                // Agregar eventos a los botones de eliminar
                const botonesEliminar = document.querySelectorAll('.btn-eliminar');
                botonesEliminar.forEach(boton => {
                    boton.addEventListener('click', eliminarProducto);
                });
            })
            .catch(error => {
                console.error('Error al cargar el carrito:', error);
                carritoItems.innerHTML = '<p>Error al cargar el carrito.</p>';
            });
    }

    // Función para eliminar un ítem del carrito
    function eliminarProducto(event) {
        const idCarrito = event.target.getAttribute('data-id');

        fetch(`../carrito/eliminar_carrito.php?id=${idCarrito}`, {
            method: 'GET',
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto eliminado del carrito.');
                    cargarCarrito(); // Recargar el carrito
                } else {
                    alert('Error al eliminar el producto.');
                }
            })
            .catch(error => {
                console.error('Error al eliminar el producto:', error);
            });
    }

    // Evento para abrir el carrito
    cartIcon.addEventListener('click', () => {
        aside.classList.add('mostrar'); // Abre el menú
        cargarCarrito(); // Carga los ítems
    });

    // Evento para cerrar el carrito
    btnClose.addEventListener('click', () => {
        aside.classList.remove('mostrar'); // Cierra el menú
    });

    // Agregar evento a los botones de "Agregar al carrito"
    botonesAgregar.forEach((boton) => {
        boton.addEventListener('click', (e) => {
            e.preventDefault(); // Prevenir cualquier comportamiento inesperado

            const idProducto = boton.dataset.id;
            console.log('Clic en botón. ID producto:', idProducto);

            fetch('../carrito/agregar_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_producto: idProducto, cantidad: 1 }),
            })
                .then(res => res.json())
                .then(data => {
                    console.log('Respuesta del servidor:', data);
                    if (data.success) {
                        actualizarContadorCarrito(); // Actualizar el contador
                    } else {
                        alert('Stock agotado');
                    }
                })
                .catch(err => console.error('Error en fetch:', err));
        });
    });

    // Inicializar el contador al cargar la página
    actualizarContadorCarrito();
    cargarCarrito();
});
