document.addEventListener('DOMContentLoaded', () => {
  renderCheckout();
});

/**
 * Obtiene el carrito desde localStorage.
 * @returns {Object} Un objeto con los productos en el carrito.
 */
function getCart() {
  return JSON.parse(localStorage.getItem('cart')) || {};
}

function renderCheckout() {
  const cart = getCart();
  const ids = Object.keys(cart);
  const container = document.getElementById('checkoutContainer');
  container.innerHTML = '';

  if (ids.length === 0) {
    container.innerHTML = '<p>No hay productos en el carrito.</p>';
    return;
  }

  fetch('../php/fetchProducts.php')
    .then(res => res.json())
    .then(productsList => {
      let subtotal = 0;

      ids.forEach(id => {
        const prod = productsList.find(p => p.id == id);
        if (!prod) return;
        const qty = cart[id];
        const price = prod.valor * qty;
        subtotal += price;

        const line = document.createElement('div');
        line.className = 'checkout-item';
        line.innerHTML = `
          <span>${prod.nombre} x${qty}</span>
          <span>$${price.toFixed(2)}</span>
        `;
        container.appendChild(line);
      });

      const itbis = subtotal * 0.18;
      const total = subtotal + itbis;

      const summary = document.createElement('div');
      summary.className = 'checkout-summary';
      summary.innerHTML = `
        <hr>
        <p>Subtotal: $${subtotal.toFixed(2)}</p>
        <p>ITBIS (18%): $${itbis.toFixed(2)}</p>
        <p><strong>Total: $${total.toFixed(2)}</strong></p>
        <button id="payButton" class="button">Realizar Pago</button>
        <div id="paypal-button-container"></div>
      `;
      container.appendChild(summary);

      // Botón de pago manual
      document.getElementById('payButton').addEventListener('click', () => {
        alert('Total a pagar: $' + total.toFixed(2));
        // Aquí puedes redirigir o enviar datos a `processOrder.php`
      });

      // Inicializar PayPal Buttons
      initializePayPalButtons(total);
    })
    .catch(err => console.error('Error al cargar productos:', err));
}

/**
 * Inicializa los botones de PayPal.
 * @param {number} total - El monto total a pagar.
 */
function initializePayPalButtons(total) {
  if (typeof paypal === 'undefined') {
    console.error('PayPal SDK no está cargado.');
    return;
  }

  paypal.Buttons({
    createOrder: (data, actions) => {
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: total.toFixed(2) // Total a pagar
          }
        }]
      });
    },
    onApprove: (data, actions) => {
      return actions.order.capture().then(details => {
        alert('Pago completado por ' + details.payer.name.given_name);
        // Redirigir a una página de "gracias" o procesar la orden en el servidor
        window.location.href = 'gracias.php';
      });
    },
    onError: err => {
      console.error('Error en PayPal:', err);
      alert('Ocurrió un error procesando el pago.');
    }
  }).render('#paypal-button-container');
}





document.getElementById('pago-ficticio').addEventListener('click', () => {
  // Validar existencia antes de proceder
  fetch('', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'action=pagar'
  })
  .then(res => res.text())
  .then(response => {
    if (response === 'ok') {
      // Vaciar el carrito
      fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=vaciar'
      }).then(() => {
        window.location.href = '../html/gracias.php';
      });
    } else {
      alert('Hubo un problema al procesar el pago. Verifica el stock.');
    }
  });
});
