document.addEventListener("DOMContentLoaded", () => {
  const formAdd = document.getElementById("form-add");
  const formEdit = document.getElementById("form-edit");
  const formDelete = document.getElementById("form-delete");
  const contenedorProductos = document.getElementById("product-list");
  const inputBusqueda = document.getElementById("searchProduct");
  const btnCargar = document.getElementById("btn-cargar-productos");

  let productosCargados = [];

  // ==========================
  // FUNCIONES DE PRODUCTOS
  // ==========================

  // Cargar productos para selects (editar/eliminar)
  async function cargarProductosSelects() {
    const selects = [
      document.querySelector("#form-edit select[name='id']"),
      document.querySelector("#form-delete select[name='id']")
    ];

    try {
      const res = await fetch("../php/listProducts.php");
      const productos = await res.json();

      selects.forEach(select => {
        select.innerHTML = `<option value="">Selecciona un producto…</option>`;
        productos.forEach(p => {
          select.innerHTML += `<option value="${p.id}">${p.nombre}</option>`;
        });
      });
    } catch (err) {
      console.error("Error cargando productos para selects:", err);
    }
  }

  // Renderizar tarjetas en listado
  function renderProductos(productos) {
    contenedorProductos.innerHTML = "";

    if (productos.length === 0) {
      contenedorProductos.innerHTML = "<p>No se encontraron productos.</p>";
      return;
    }

    productos.forEach(prod => {
      const card = document.createElement("div");
      card.className = "product-card";
      card.innerHTML = `
        <img src="${prod.urlImagen}" alt="${prod.nombre}" class="product-img" />
        <h3>${prod.nombre}</h3>
        <p><strong>Precio:</strong> RD$ ${parseFloat(prod.valor).toFixed(2)}</p>
        <p><strong>Existencia:</strong> ${prod.existencia}</p>
        <p><strong>Categoría:</strong> ${prod.categoria}</p>
      `;
      contenedorProductos.appendChild(card);
    });
  }

  // Cargar productos y mostrarlos
  async function cargarYMostrarProductos() {
    contenedorProductos.innerHTML = `
      <div class="loading-container">
        <div class="loader"></div>
        <p>Cargando productos…</p>
      </div>
    `;
  
    try {
      const res = await fetch("../php/listProducts.php");
      productosCargados = await res.json();
      renderProductos(productosCargados);
    } catch (error) {
      console.error("Error al cargar productos:", error);
      contenedorProductos.innerHTML = "<p style='color:red;'>Error al cargar productos.</p>";
    }
  }
  

  // ==========================
  // EVENTOS
  // ==========================

  // Añadir producto
  formAdd.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(formAdd);

    try {
      const response = await fetch("../php/addProduct.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert("Producto añadido exitosamente.");
        formAdd.reset();
        cargarProductosSelects(); // actualiza selects
      } else {
        alert("Error al añadir producto.");
      }
    } catch (error) {
      console.error("Error en la petición:", error);
      alert("Error al procesar la solicitud.");
    }
  });

  // Editar producto
  formEdit.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(formEdit);

    try {
      const res = await fetch("../php/editProduct.php", {
        method: "POST",
        body: formData,
      });

      const result = await res.json();

      if (result.success) {
        alert("Producto editado correctamente.");
        formEdit.reset();
        cargarProductosSelects();
      } else {
        alert("Error al editar el producto.");
      }
    } catch (error) {
      console.error("Error al editar:", error);
      alert("Hubo un problema al editar el producto.");
    }
  });

  // Eliminar producto
  formDelete.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(formDelete);
    const confirmacion = confirm("¿Estás seguro de que deseas eliminar este producto?");
    if (!confirmacion) return;

    try {
      const res = await fetch("../php/deleteProduct.php", {
        method: "POST",
        body: formData,
      });

      const result = await res.json();

      if (result.success) {
        alert("Producto eliminado correctamente.");
        formDelete.reset();
        cargarProductosSelects();
      } else {
        alert("Error al eliminar el producto.");
      }
    } catch (error) {
      console.error("Error al eliminar:", error);
      alert("Ocurrió un error al intentar eliminar el producto.");
    }
  });

  // Buscar productos
  inputBusqueda.addEventListener("input", () => {
    const texto = inputBusqueda.value.toLowerCase();
    const filtrados = productosCargados.filter(p =>
      p.nombre.toLowerCase().includes(texto) ||
      p.categoria.toLowerCase().includes(texto)
    );
    renderProductos(filtrados);
  });

  // Botón de cargar productos
  btnCargar.addEventListener("click", () => {
    cargarYMostrarProductos();
  });

  // ==========================
  // MANEJO DE TABS
  // ==========================

  const tabs = document.querySelectorAll(".tabs button");
  const panels = document.querySelectorAll(".panel");

  tabs.forEach(tab => {
    tab.addEventListener("click", () => {
      tabs.forEach(t => t.classList.remove("active"));
      panels.forEach(p => p.classList.remove("active"));
  
      tab.classList.add("active");
      const panelId = tab.id.replace("tab", "panel");
      document.getElementById(panelId).classList.add("active");
  
      if (panelId === "panel-list") {
        cargarYMostrarProductos(); // carga automática al abrir "Listar"
      }
    });
  });

  // Inicializar selects
  cargarProductosSelects();
});

const inputBuscar = document.getElementById("searchProduct");

inputBuscar.addEventListener("input", () => {
  const texto = inputBuscar.value.toLowerCase().trim();

  const filtrados = productosCargados.filter(p =>
    p.nombre.toLowerCase().includes(texto) ||
    p.categoria.toLowerCase().includes(texto)
  );

  renderProductos(filtrados);
});

