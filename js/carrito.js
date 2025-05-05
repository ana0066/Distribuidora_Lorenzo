console.log('🍀 carrito.js cargado');

// Array para almacenar los productos en el carrito
let cart = getCart(); // Cargar el carrito desde localStorage al iniciar

// Clave en localStorage
const CART_KEY = 'cart';

// ── LOCALSTORAGE ───────────────────────────────────────────────────────────────
function getCart() {
    return JSON.parse(localStorage.getItem(CART_KEY)) || []; // Devuelve un array vacío si no hay carrito
}

function saveCart(cart) {
    localStorage.setItem(CART_KEY, JSON.stringify(cart)); // Guarda el carrito en localStorage
}

// ── CONTADOR ──────────────────────────────────────────────────────────────────
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const totalItems = cart.reduce((sum, product) => sum + product.quantity, 0);
    document.getElementById('cart-count').textContent = totalItems;
}

// ── AGREGAR, CAMBIAR, ELIMINAR ────────────────────────────────────────────────
function addToCart(product) {
    let cart = JSON.parse(localStorage.getItem('cart')) || []; // Obtén el carrito del localStorage
    const existingProduct = cart.find(item => item.id === product.id);

    if (existingProduct) {
        existingProduct.quantity += 1; // Incrementa la cantidad si el producto ya existe
    } else {
        cart.push({ ...product, quantity: 1 }); // Agrega el producto al carrito
    }

    localStorage.setItem('cart', JSON.stringify(cart)); // Guarda el carrito actualizado en localStorage
    renderCart(); // Actualiza la vista del carrito
    updateCartCount(); // Actualiza el contador del carrito
}

function changeQuantity(productId, qty) {
    const product = cart.find(item => item.id === productId);
    if (product) {
        if (qty <= 0) {
            removeFromCart(productId); // Eliminar si la cantidad es 0 o menor
        } else {
            product.quantity = qty; // Actualizar la cantidad
        }
        saveCart(cart); // Guardar los cambios en localStorage
        renderCart(); // Actualizar la vista del carrito
        updateCartCount(); // Actualizar el contador
    }
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId); // Eliminar el producto del carrito
    saveCart(cart); // Guardar los cambios en localStorage
    renderCart(); // Actualizar la vista del carrito
    updateCartCount(); // Actualizar el contador
}

// ── RENDERIZAR CARRITO ───────────────────────────────────────────────────────
function renderCart() {
    const cartItemsContainer = document.getElementById('cartItems');
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    cartItemsContainer.innerHTML = '';

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p>El carrito está vacío.</p>';
        return;
    }

    cart.forEach(product => {
        const cartItem = document.createElement('div');
        cartItem.classList.add('cart-item');

        cartItem.innerHTML = `
            <img src="${product.image}" alt="${product.name}" style="width: 50px; height: 50px;">
            <div>
                <p>${product.name}</p>
                <p>Cantidad: ${product.quantity}</p>
                <p>Precio: $${(product.price * product.quantity).toFixed(2)}</p>
            </div>
            <button onclick="removeFromCart(${product.id})">Eliminar</button>
        `;

        cartItemsContainer.appendChild(cartItem);
    });
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
        renderCart(); // Renderizar el carrito al abrirlo
    }
}

// ── TOTAL DEL CARRITO ────────────────────────────────────────────────────────
function updateCartTotal() {
    const total = cart.reduce((sum, product) => sum + product.price * product.quantity, 0);
    document.getElementById('cart-total').textContent = `$${total.toFixed(2)}`;
}

// ── CHECKOUT ─────────────────────────────────────────────────────────────────
function checkout() {
    if (cart.length === 0) {
        alert('El carrito está vacío.');
        return;
    }
    alert('Redirigiendo al checkout...');
    // Aquí puedes redirigir al usuario a la página de checkout
    window.location.href = 'checkout.php';
}

// ── INICIALIZACIÓN ───────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    renderCart(); // Renderiza el carrito al cargar la página
    updateCartCount(); // Actualiza el contador del carrito
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
