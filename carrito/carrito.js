document.addEventListener('DOMContentLoaded', () => {
    const botonesAgregar = document.querySelectorAll('.btn-agregar-carrito');
    const cartCount = document.getElementById('cart-count');
    const cartIcon = document.getElementById('cartIcon');
    const aside = document.getElementById('asideCarrito');
    const btnClose = document.getElementById('btnCerrarCarrito'); // Botón para cerrar el carrito
    const carritoItems = document.querySelector('.carrito-items'); // Contenedor de los ítems del carrito

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
    function loadCart() {
        console.log("Cargando carrito...");
        fetch('../carrito/obtener_carrito.php')
            .then(res => res.json())
            .then(data => {
                console.log('Ítems del carrito:', data);

                // Limpiar el contenedor antes de agregar nuevos ítems
                carritoItems.innerHTML = '';

                if (data.items && data.items.length > 0) {
                    data.items.forEach(item => {
                        const itemHTML = `
                            <div class="item-carrito">
                                <img src="${item.urlImagen}" alt="${item.nombre}" class="carrito-item-img">
                                <div class="item-info">
                                    <h4>${item.nombre}</h4>
                                    <p>Cantidad: ${item.cantidad}</p>
                                    <p>Precio: ${item.valor} DOP</p>
                                    <p>Subtotal: ${item.subtotal} DOP</p>
                                </div>
                                <button class="btn-eliminar-item" data-id="${item.id}">✕</button>
                            </div>
                        `;
                        carritoItems.insertAdjacentHTML('beforeend', itemHTML);
                    });

                    // Agregar eventos a los botones de eliminar
                    const botonesEliminar = document.querySelectorAll('.btn-eliminar-item');
                    botonesEliminar.forEach(boton => {
                        boton.addEventListener('click', (e) => {
                            const idProducto = boton.dataset.id;
                            eliminarItemCarrito(idProducto);
                        });
                    });
                } else {
                    carritoItems.innerHTML = '<p>Tu carrito está vacío.</p>';
                }
            })
            .catch(err => console.error('Error cargando el carrito:', err));
    }

    // Función para eliminar un ítem del carrito
    function eliminarItemCarrito(idProducto) {
        fetch(`../carrito/eliminar_carrito.php?id=${idProducto}`, {
            method: 'GET',
        })
            .then(res => res.json())
            .then(data => {
                console.log('Ítem eliminado:', data);
                if (data.success) {
                    loadCart(); // Recargar el carrito
                    actualizarContadorCarrito(); // Actualizar el contador
                } else {
                    alert('Error al eliminar el ítem del carrito');
                }
            })
            .catch(err => console.error('Error eliminando ítem:', err));
    }

    // Evento para abrir el carrito
    cartIcon.addEventListener('click', () => {
        aside.classList.add('mostrar'); // Abre el menú
        loadCart(); // Carga los ítems
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
                        alert('Error al agregar al carrito');
                    }
                })
                .catch(err => console.error('Error en fetch:', err));
        });
    });

    // Inicializar el contador al cargar la página
    actualizarContadorCarrito();
});
