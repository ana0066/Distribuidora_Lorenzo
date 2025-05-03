document.addEventListener('DOMContentLoaded', () => {
  const filtro = document.getElementById('filtroCategoria');
  const contenedor = document.getElementById('productosContainer');
  const cartCountElement = document.getElementById('cart-count');

  // Inicializar el contador del carrito desde localStorage
  const cart = JSON.parse(localStorage.getItem('cart')) || [];
  cartCountElement.textContent = cart.length;

  // Carga inicial de productos
  loadProducts('');

  // Al cambiar categoría
  filtro.addEventListener('change', () => {
    loadProducts(filtro.value);
  });
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
      <button data-id="${prod.id}">Agregar al carrito</button>
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

}
