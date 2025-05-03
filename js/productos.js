document.addEventListener('DOMContentLoaded', () => {
  const filtro = document.getElementById('filtroCategoria');
  const contenedor = document.getElementById('productosContainer');
  const cartCountElement = document.getElementById('cart-count');
  const cartAside = document.getElementById('cart-aside');
  const cartToggle = document.getElementById('cart-toggle');
  const closeCart = document.getElementById('close-cart');
  const cartItemsContainer = document.querySelector('.cart-items');
  const cartTotalElement = document.getElementById('cart-total');

  // Inicializar el contador del carrito desde localStorage
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  cartCountElement.textContent = cart.length;

  // Carga inicial de productos
  loadProducts('');

  // Al cambiar categoría
  filtro.addEventListener('change', () => {
    loadProducts(filtro.value);
  });

  // Abrir el carrito
  cartToggle.addEventListener('click', () => {
    cartAside.classList.add('open');
    renderCartItems();
  });

  // Cerrar el carrito
  closeCart.addEventListener('click', () => {
    cartAside.classList.remove('open');
  });

  // Renderizar los productos del carrito
  function renderCartItems() {
    cartItemsContainer.innerHTML = ''; // Limpiar el contenedor antes de renderizar

    let total = 0;

    cart.forEach(productId => {
      // Aquí deberías obtener los detalles del producto usando el productId
      // Por simplicidad, asumamos que tienes una función getProductById que devuelve el producto
      const product = getProductById(productId);

      const item = document.createElement('div');
      item.className = 'cart-item';
      item.innerHTML = `
        <img src="${product.urlImagen}" alt="${product.nombre}">
        <div>
          <h4>${product.nombre}</h4>
          <p>Valor: $${parseFloat(product.valor).toFixed(2)}</p>
        </div>
        <button class="remove-item" data-id="${productId}">Eliminar</button>
      `;

      // Agregar evento para eliminar el producto del carrito
      item.querySelector('.remove-item').addEventListener('click', () => {
        removeFromCart(productId);
      });

      cartItemsContainer.appendChild(item);

      total += parseFloat(product.valor);
    });

    cartTotalElement.textContent = `Total: $${total.toFixed(2)}`;
  }

  // Función para obtener los detalles del producto (deberías implementarla)
  function getProductById(productId) {
    // Implementa esta función para obtener los detalles del producto
    // Por ejemplo, podrías buscar en un array de productos o hacer una petición a la API
  }

  // Función para eliminar un producto del carrito
  function removeFromCart(productId) {
    cart = cart.filter(id => id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    cartCountElement.textContent = cart.length;
    renderCartItems();
  }
});

/**
 * Fetch de productos y render en pantalla.
 * @param {string} categoria – cadena vacía para todas o el slug de categoría.
 */
function loadProducts(categoria) {
  // Construir URL con filtro (solo si hay valor)
  let url = '../server/fetchProducts.php'; // Ajusta la ruta si es necesario
  if (categoria) {
    url += '?categoria=' + encodeURIComponent(categoria);
  }

  console.log('URL solicitada:', url);

  fetch(url)
    .then(res => {
      if (!res.ok) {
        throw new Error(`HTTP error! status: ${res.status}`);
      }
      return res.json();
    })
    .then(data => {
      renderProducts(data);
    })
    .catch(err => {
      console.error('Error al cargar productos:', err);
    });
}

/**
 * Renderiza un array de productos en el DOM.
 * @param {Array} productos – cada objeto tiene id, nombre, valor, existencia, urlImagen, categoria.
 */
function renderProducts(productos) {
  const contenedor = document.getElementById('productosContainer');
  contenedor.innerHTML = ''; // limpiar antes

  if (productos.length === 0) {
    contenedor.innerHTML = '<p>No hay productos en esta categoría.</p>';
    return;
  }

  productos.forEach(prod => {
    const card = document.createElement('div');
    card.className = 'producto-card';
    card.innerHTML = `
      <img src="${prod.urlImagen}" alt="${prod.nombre}">
      <h3>${prod.nombre}</h3>
      <p>Valor: $${parseFloat(prod.valor).toFixed(2)}</p>
      <p>Categoría: ${prod.categoria}</p>
      <button data-id="${prod.id}">Agregar al carrito <i class='fas fa-shopping-cart'></i> </button>
    `;

    const btn = card.querySelector('button');
    btn.addEventListener('click', () => {
      addToCart(prod.id);
    });

    contenedor.appendChild(card);
  });
}

/**
 * Agrega un producto al carrito y actualiza el contador.
 * @param {number} productId - ID del producto a agregar.
 */
function addToCart(productId) {
  const cartCountElement = document.getElementById('cart-count');

  // Obtener el carrito actual desde localStorage
  let cart = JSON.parse(localStorage.getItem('cart')) || [];

  // Agregar el producto al carrito
  cart.push(productId);

  // Guardar el carrito actualizado en localStorage
  localStorage.setItem('cart', JSON.stringify(cart));

  // Actualizar el contador del carrito
  cartCountElement.textContent = cart.length;

  localStorage.setItem('carrito', JSON.stringify(carrito)); // Guardar en LocalStorage
    actualizarCarrito();

}
