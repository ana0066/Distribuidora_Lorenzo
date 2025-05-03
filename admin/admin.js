document.addEventListener("DOMContentLoaded", () => {
  cargarProductos(); 
  mostrarProductos();

  // 🔹 Añadir Producto
  document.getElementById("botonAñadir").addEventListener("click", function () {
      const data = {
          nombre: document.getElementById("productoAñadir").value,
          valor: parseFloat(document.getElementById("valorAñadir").value),
          existencia: parseInt(document.getElementById("existenciaAñadir").value),
          urlImagen: document.getElementById("ImagenAñadir").value,
          categoria: document.getElementById("categoriaAñadir").value, // Nuevo campo para categorías
      };

      fetch("../php/addProduct.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
      })
      .then(response => response.json())
      .then(result => {
          alert(result.message || result.error);
          if (result.message) {
              cargarProductos();
              mostrarProductos();
          }
      })
      .then(result => {
        alert(result.message || result.error);
        if (result.message) {
            cargarProductos();
            mostrarProductos();
    
            // 🔹 Vaciar campos después de agregar exitosamente
            document.getElementById("productoAñadir").value = "";
            document.getElementById("valorAñadir").value = "";
            document.getElementById("existenciaAñadir").value = "";
            document.getElementById("ImagenAñadir").value = "";
            document.getElementById("categoriaAñadir").value = "";
        }
    })
      .catch(error => console.error("Error:", error));
  });

  // 🔹 Editar Producto
  document.getElementById("botonEditar").addEventListener("click", function () {
      const idProducto = document.getElementById("productoEditar").value;
      const atributo = document.getElementById("atributoEditar").value;
      const nuevoValor = document.getElementById("nuevoAtributo").value;

      if (!idProducto || !atributo || !nuevoValor) {
          alert("Por favor, completa todos los campos para editar.");
          return;
      }

      const data = { id: idProducto, atributo, nuevoValor };

      fetch("../php/editProduct.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
      })
      .then(response => response.json())
      .then(result => {
          alert(result.message || result.error);
          if (result.message) {
              cargarProductos();
              mostrarProductos();
          }
      })
      .catch(error => console.error("Error:", error));
  });

  // 🔹 Eliminar Producto
  document.getElementById("botonEliminar").addEventListener("click", function () {
      const idProducto = document.getElementById("productoEliminar").value;

      if (!idProducto) {
          alert("Selecciona un producto para eliminar.");
          return;
      }

      fetch("../php/deleteProduct.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: idProducto }),
      })
      .then(response => response.json())
      .then(result => {
          alert(result.message || result.error);
          if (result.message) {
              cargarProductos();
              mostrarProductos();
          }
      })
      .catch(error => console.error("Error:", error));
  });
});

// Función para cargar productos en los selects
function cargarProductos() {
  fetch("../php/fetchProducts.php")
      .then(response => response.json())
      .then(data => {
          let selectEditar = document.getElementById("productoEditar");
          let selectEliminar = document.getElementById("productoEliminar");

          // Limpiar selects antes de agregar opciones
          selectEditar.innerHTML = '<option value="">---</option>';
          selectEliminar.innerHTML = '<option value="">---</option>';

          data.forEach(producto => {
              let option = document.createElement("option");
              option.value = producto.id;
              option.textContent = producto.nombre;
              selectEditar.appendChild(option);
              selectEliminar.appendChild(option.cloneNode(true));
          });

          // Llenar el select de atributos
          let selectAtributo = document.getElementById("atributoEditar");
          selectAtributo.innerHTML = `
              <option value="">---</option>
              <option value="nombre">Nombre</option>
              <option value="valor">Valor</option>
              <option value="existencia">Existencia</option>
              <option value="urlImagen">Imagen</option>
              <option value="categoria">Categoría</option>
          `;
      })
      .catch(error => console.error("Error cargando productos:", error));
}


// 🔹 Mostrar productos en la interfaz
function mostrarProductos() {
  fetch("../php/fetchProducts.php")
      .then(response => response.json())
      .then(data => {
          let contenedor = document.getElementById("mostrarProductos");
          contenedor.innerHTML = ""; // Limpiar antes de actualizar

          data.forEach(producto => {
              let productoHTML = `
                  <div class="producto-card">
                      <img src="${producto.urlImagen}" alt="${producto.nombre}" class="producto-imagen">
                      <h3>${producto.nombre}</h3>
                      <p>Valor: $${producto.valor}</p>
                      <p>Existencia: ${producto.existencia}</p>
                      <p>Categoría: ${producto.categoria}</p> <!-- Mostrar categoría -->
                  </div>
              `;
              contenedor.innerHTML += productoHTML;
          });
      })
      .catch(error => console.error("Error mostrando productos:", error));
}
