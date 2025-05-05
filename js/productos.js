document.addEventListener('DOMContentLoaded', () => {
  const filtro = document.getElementById('filtroCategoria');
  const contenedor = document.getElementById('productosContainer');

  // Fetch y render inicial
  loadProducts('');

  // Re-render al cambiar categoría
  filtro.addEventListener('change', () => {
    loadProducts(filtro.value);
  });

  window.loadProducts = function(categoria) {
    let url = '../server/fetchProducts.php';
    if (categoria) {
      url += '?categoria=' + encodeURIComponent(categoria);
    }
    fetch(url)
      .then(res => res.json())
      .then(data => renderProducts(data))
      .catch(err => console.error(err));
  };

  function renderProducts(productos) {
    contenedor.innerHTML = '';
    if (!productos.length) {
      contenedor.innerHTML = '<p class="empty">No hay productos en esta categoría.</p>';
      return;
    }
    productos.forEach(prod => {
      const card = document.createElement('div');
      card.className = 'card-producto';
      card.innerHTML = `
        <img src="${prod.urlImagen}" alt="${prod.nombre}">
        <div class="info">
          <h3>${prod.nombre}</h3>
          <p><span class="precio">$${parseFloat(prod.valor).toFixed(2)}</span></p>
          <p>Categoría: ${prod.categoria}</p>
        </div>
        <div class="actions">
          <button data-id="${prod.id}"><i class="fas fa-shopping-cart"></i> Agregar</button>
        </div>
      `;
      // Evento addToCart (de tu carrito.js)
      card.querySelector('button').addEventListener('click', () => {
        addToCart(prod);
      });
      contenedor.appendChild(card);
    });
  }
});
