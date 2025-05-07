document.addEventListener('DOMContentLoaded', () => {
    const cartIcon = document.getElementById('cartIcon'); 
    const asideCarrito = document.getElementById('asideCarrito'); 
    const btnCerrarCarrito = document.getElementById('btnCerrarCarrito'); 
    const cartCount = document.getElementById('cart-count'); 

    // Función para abrir el carrito
    cartIcon.addEventListener('click', () => {
        asideCarrito.classList.add('mostrar'); 
        loadCart();
    });

    // Función para cerrar el carrito
    btnCerrarCarrito.addEventListener('click', () => {
        asideCarrito.classList.remove('mostrar'); 
    });

    // Función para cargar los ítems del carrito
    function loadCart() {
        console.log("Cargando carrito...");
        fetch('../carrito/obtener.php') // Cambia la ruta según tu estructura
            .then(res => res.json())
            .then(data => {
                console.log('Ítems del carrito:', data);

                // Selecciona el contenedor de los ítems del carrito
                const carritoItems = document.querySelector('.carrito-items');

                // Limpiar el contenedor antes de agregar nuevos ítems
                carritoItems.innerHTML = '';

                if (data.items && data.items.length > 0) {
                    // Iterar sobre los productos y renderizarlos
                    data.items.forEach(item => {
                        const itemHTML = `
                            <div class="carrito-item">
                                <img src="${item.imagen}" alt="${item.nombre}" class="carrito-item-img">
                                <div class="carrito-item-info">
                                    <h4>${item.nombre}</h4>
                                    <p>Cantidad: ${item.cantidad}</p>
                                    <p>Precio: ${item.precio} DOP</p>
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
                            eliminarItemCarrito(idProducto, e);
                        });
                    });
                } else {
                    // Si no hay productos, mostrar un mensaje
                    carritoItems.innerHTML = '<p>Tu carrito está vacío.</p>';
                }

                // Actualizar el total del carrito
                document.getElementById('carrito-total').textContent = `${data.total.toFixed(2)} DOP`;
            })
            .catch(err => console.error('Error cargando el carrito:', err));
    }

    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        fetch('../carrito/carrito_total.php') // Cambia la ruta según tu estructura
            .then(res => res.json())
            .then(data => {
                const cartCount = document.getElementById('cart-count');
                cartCount.textContent = data.total || 0; // Actualiza el contador
            })
            .catch(err => console.error('Error actualizando el contador del carrito:', err));
    }

    function actualizarCantidad(id, cantidad, stock, e) {
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=actualizar&id_producto=${id}&cantidad=${cantidad}`
        }).then(res => res.text())
          .then(response => {
              if (response === 'error') {
                  alert('No se pudo actualizar la cantidad. Verifica el stock.');
                  e.target.value = stock; // Revertir al stock máximo
              } else {
                  calcularTotales();
                  actualizarContadorCarrito(); // Actualiza el contador
              }
          });
    }

    function eliminarItemCarrito(id, e) {
        fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `action=eliminar&id_producto=${id}`
        }).then(() => {
            e.target.closest('tr').remove();
            calcularTotales();
            actualizarContadorCarrito(); // Actualiza el contador
        });
    }

    function calcularTotales() {
        let subtotal = 0;

        // Iterar sobre cada fila del carrito para calcular los totales
        document.querySelectorAll('.carrito-item').forEach(item => {
            const cantidad = parseInt(item.querySelector('.input-cantidad').value);
            const precio = parseFloat(item.querySelector('.precio-unitario').textContent);
            const subtotalItem = cantidad * precio;

            // Actualizar el subtotal del ítem
            item.querySelector('.subtotal-item').textContent = subtotalItem.toFixed(2) + ' DOP';

            // Sumar al subtotal general
            subtotal += subtotalItem;
        });

        // Calcular ITBIS (18% del subtotal)
        const itbis = subtotal * 0.18;

        // Calcular el total
        const total = subtotal + itbis;

        // Actualizar los valores en el DOM
        document.getElementById('subtotal').textContent = subtotal.toFixed(2) + ' DOP';
        document.getElementById('itbis').textContent = itbis.toFixed(2) + ' DOP';
        document.getElementById('total').textContent = total.toFixed(2) + ' DOP';
    }

    calcularTotales();
    actualizarContadorCarrito();
});
