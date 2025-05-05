// Agregar producto al carrito
function agregarAlCarrito(id_producto) {
    fetch('../carrito/carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `accion=agregar&id_producto=${id_producto}&cantidad=1`
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            alert('Producto agregado al carrito.');
            cargarCarrito();
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(err => {
        console.error('Error al agregar al carrito:', err);
    });
}

// Cargar contenido del carrito
function cargarCarrito() {
    fetch('../carrito/carrito.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ accion: 'obtener' })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Asegúrate de acceder al array dentro de "data"
            const carrito = data.data; // Aquí está el array de productos
            carrito.forEach(producto => {
                console.log(`Producto: ${producto.nombre}, Cantidad: ${producto.cantidad}`);
                // Aquí puedes renderizar los productos en el carrito
            });
        } else {
            console.error('Error:', data.error);
        }
    })
    .catch(error => console.error('Error al cargar el carrito:', error));
}

// Eliminar producto del carrito
function eliminarDelCarrito(id_producto) {
    fetch('../carrito/carrito.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `accion=eliminar&id_producto=${id_producto}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.ok) {
            cargarCarrito();
        } else {
            alert('No se pudo eliminar el producto.');
        }
    })
    .catch(err => {
        console.error('Error al eliminar del carrito:', err);
    });
}

// Cargar carrito al cargar la página
document.addEventListener('DOMContentLoaded', () => {
    cargarCarrito();
});

function toggleCart() {
    const modal = document.getElementById('cartModal');
    modal.classList.toggle('visible'); // Asegúrate de tener clase .visible en CSS
}
