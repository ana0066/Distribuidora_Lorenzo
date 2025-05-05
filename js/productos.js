let productos = [];  // Todos los productos cargados
let productosFiltrados = []; // Productos tras aplicar filtro
const contenedor = document.getElementById('productosContainer');

document.addEventListener('DOMContentLoaded', function () {
  const contenedor = document.getElementById('productosContainer');
  contenedor.innerHTML = ''; // ahora sí, está definido
});

document.addEventListener('DOMContentLoaded', () => {
  cargarProductos();

  const filtro = document.getElementById('filtroCategoria');
  filtro.addEventListener('change', () => {
      cargarProductos(filtro.value);
  });
});

function cargarProductos(categoria = '') {
  fetch('../php/fetchProducts.php')
      .then(res => res.json())
      .then(data => {
          const contenedor = document.getElementById('productosContainer');
          contenedor.innerHTML = '';

          const productosFiltrados = categoria
              ? data.filter(p => p.categoria === categoria)
              : data;

          productosFiltrados.forEach(producto => {
              const card = document.createElement('div');
              card.className = 'card-producto';

              card.innerHTML = `
                  <img src="${producto.urlImagen}" alt="${producto.nombre}">
                  <h3>${producto.nombre}</h3>
                  <p>Precio: $${producto.valor}</p>
              `;

              const boton = document.createElement('button');
              boton.textContent = 'Comprar';
              boton.onclick = () => agregarAlCarrito(producto.id);

              card.appendChild(boton);
              contenedor.appendChild(card);
          });
      });
} if (productosFiltrados.length === 0) {
  contenedor.innerHTML = '<p>No hay productos en esta categoría.</p>';
}

