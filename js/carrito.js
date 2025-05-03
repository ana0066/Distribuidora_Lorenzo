console.log('🍀 carrito.js v2 cargado');

function toggleCart() {
  console.log('🔔 toggleCart disparado');
  const modal = document.getElementById('cartModal');
  console.log('   modal =', modal);
  if (!modal) {
    console.error('toggleCart: elemento #cartModal no existe');
    return;
  }
  modal.classList.toggle('visible');
  if (modal.classList.contains('visible')) renderCart();
}

// Clave en localStorage
const CART_KEY = 'cart';

// ── LOCALSTORAGE ───────────────────────────────────────────────────────────────
function getCart() {
  return JSON.parse(localStorage.getItem(CART_KEY)) || {};
}

function saveCart(cart) {
  localStorage.setItem(CART_KEY, JSON.stringify(cart));
}

// ── CONTADOR ──────────────────────────────────────────────────────────────────
function updateCartCount() {
  const countEl = document.getElementById('cart-count');
  if (!countEl) return;
  const cart = getCart();
  const totalItems = Object.values(cart).reduce((sum, qty) => sum + qty, 0);
  countEl.textContent = totalItems;
}

// ── AGREGAR, CAMBIAR, ELIMINAR ────────────────────────────────────────────────
function addToCart(id) {
  const cart = getCart();
  cart[id] = (cart[id] || 0) + 1;
  saveCart(cart);
  updateCartCount();
}

function changeQuantity(id, qty) {
  const cart = getCart();
  if (qty <= 0) {
    delete cart[id];
  } else {
    cart[id] = qty;
  }
  saveCart(cart);
  updateCartCount();
}

function removeFromCart(id) {
  const cart = getCart();
  delete cart[id];
  saveCart(cart);
  updateCartCount();
}

// ── RENDERIZAR CARRITO ───────────────────────────────────────────────────────
function renderCart() {
  const cart = getCart();
  const container = document.getElementById('cartItems');
  container.innerHTML = '';

  const ids = Object.keys(cart);
  if (ids.length === 0) {
    container.innerHTML = '<p>El carrito está vacío.</p>';
    return;
  }

  // Cargo todos los productos y filtro
  fetch('../php/fetchProducts.php')
    .then(res => res.json())
    .then(productsList => {
      ids.forEach(id => {
        const prod = productsList.find(p => p.id == id);
        if (!prod) return;
        const qty = cart[id];
        const item = document.createElement('div');
        item.className = 'cart-item';
        item.innerHTML = `
          <img src="${prod.urlImagen}" alt="${prod.nombre}">
          <div class="cart-info">
            <p>${prod.nombre}</p>
            <input type="number" min="1" value="${qty}" data-id="${id}" class="cart-qty">
            <p>$${(prod.valor * qty).toFixed(2)}</p>
          </div>
          <button class="remove-item" data-id="${id}">×</button>
        `;
        container.appendChild(item);
      });

      // Listeners para cambiar cantidad
      container.querySelectorAll('.cart-qty').forEach(input => {
        input.addEventListener('change', e => {
          const id = e.target.dataset.id;
          const newQty = parseInt(e.target.value);
          if (isNaN(newQty) || newQty <= 0) {
            removeFromCart(id);
          } else {
            changeQuantity(id, newQty);
          }
          renderCart();
        });
      });

      // Listeners para eliminar producto
      container.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', e => {
          removeFromCart(e.target.dataset.id);
          renderCart();
        });
      });
    })
    .catch(err => console.error('Error al cargar productos:', err));
}

// ── MOSTRAR / OCULTAR ─────────────────────────────────────────────────────────
function toggleCart() {
  const modal = document.getElementById('cartModal');
  if (!modal) {
    console.error('toggleCart: elemento #cartModal no existe');
    return;
  }
  modal.classList.toggle('visible');
  if (modal.classList.contains('visible')) {
    renderCart();
  }
}

// ── INICIALIZACIÓN ───────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  updateCartCount();
});

document.addEventListener("DOMContentLoaded", () => {
    const cartToggle = document.getElementById("cart-toggle");
    const cartAside = document.getElementById("cart-aside");
    const closeCart = document.getElementById("close-cart");

    // Abrir el carrito
    cartToggle.addEventListener("click", () => {
        cartAside.classList.add("open");
    });

    // Cerrar el carrito
    closeCart.addEventListener("click", () => {
        cartAside.classList.remove("open");
    });

    // Cerrar el carrito al hacer clic fuera de él
    document.addEventListener("click", (e) => {
        if (!cartAside.contains(e.target) && !cartToggle.contains(e.target)) {
            cartAside.classList.remove("open");
        }
    });
});
