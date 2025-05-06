// js/carrito.js
(function () {
    const cartIcon = document.getElementById('cartIcon');
    const aside = document.getElementById('asideCarrito');
    const btnClose = document.getElementById('btnCerrarCarrito');
    const list = document.getElementById('carritoItems');
    const countEl = document.getElementById('cart-count');
    const totalEl = document.getElementById('totalCarrito');
  
    // Mostrar el carrito
    cartIcon.addEventListener('click', () => {
      aside.classList.add('mostrar');
      loadCart();
    });
  
    // Cerrar el carrito
    btnClose.addEventListener('click', () => aside.classList.remove('mostrar'));
  
    // Cargar el carrito
    function loadCart() {
      fetch('../carrito/obtener_carrito.php')
        .then((r) => r.json())
        .then((data) => {
          list.innerHTML = '';
          let total = 0,
            cnt = 0;
          data.items.forEach((it) => {
            const valor = parseFloat(it.valor);
            const sub = parseFloat(it.subtotal);
            total += sub;
            cnt += parseInt(it.cantidad);
            const div = document.createElement('div');
            div.className = 'item-carrito';
            div.innerHTML = `
              <img src="${it.urlImagen}" alt="${it.nombre}">
              <div class="item-info">
                <h4>${it.nombre}</h4>
                <p class="precio">RD$${valor.toFixed(2)}</p>
                <p class="subtotal">Sub: RD$${sub.toFixed(2)}</p>
                <input type="number" min="1" value="${it.cantidad}" data-id="${it.id_carrito}">
              </div>
              <button class="eliminar" data-id="${it.id_carrito}">×</button>
            `;
            list.appendChild(div);
          });
          countEl.textContent = cnt;
          totalEl.textContent = 'RD$' + total.toFixed(2);
  
          // Eventos para actualizar cantidad
          list.querySelectorAll('input[type="number"]').forEach((input) => {
            input.addEventListener('change', (e) => {
              updateItem(e.target.dataset.id, parseInt(e.target.value, 10));
            });
          });
  
          // Eventos para eliminar producto
          list.querySelectorAll('.eliminar').forEach((btn) => {
            btn.addEventListener('click', (e) => {
              removeItem(e.target.dataset.id);
            });
          });
        });
    }
  
    // Actualizar un ítem del carrito
    function updateItem(id, qty) {
      fetch('../carrito/actualizar_carrito.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_carrito: id, cantidad: qty }),
      })
        .then((r) => r.json())
        .then((resp) => {
          if (resp.ok) {
            loadCart();
            actualizarContadorCarrito();
          }
        });
    }
  
    // Eliminar un ítem del carrito
    function removeItem(id) {
      fetch('../carrito/eliminar_carrito.php?id=' + id)
        .then((r) => r.json())
        .then((resp) => {
          if (resp.ok) {
            loadCart();
            actualizarContadorCarrito();
          }
        });
    }
  
    // Actualizar el contador del carrito
    function actualizarContadorCarrito() {
      fetch('../carrito/carrito_total.php')
        .then((response) => response.json())
        .then((data) => {
          countEl.textContent = data.total || 0;
        });
    }
  
    // Inicializar contador al cargar la página
    document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
  })();
  
  // Manejo de agregar producto sin recargar
  document.addEventListener('DOMContentLoaded', () => {
    const botonesAgregar = document.querySelectorAll('.btn-agregar-carrito');

    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        fetch('../carrito/carrito_total.php')
            .then(res => res.json())
            .then(data => {
                const cartCount = document.getElementById('cart-count');
                if (cartCount) cartCount.textContent = data.total || 0;
            });
    }

    // Agregar evento a los botones de "Agregar al carrito"
    botonesAgregar.forEach(boton => {
        boton.addEventListener('click', (e) => {
            e.preventDefault(); // Evita recargar la página
            const idProducto = boton.dataset.id;

            // Enviar solicitud para agregar al carrito
            fetch('../carrito/agregar_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_producto: idProducto, cantidad: 1 }),
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        actualizarContadorCarrito(); // Actualiza el contador dinámicamente
                    } else {
                        alert('No se pudo agregar al carrito');
                    }
                });
        });
    });

    // Inicializar el contador al cargar la página
    actualizarContadorCarrito();
});
