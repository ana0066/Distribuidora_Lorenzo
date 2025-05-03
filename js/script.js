const buscador = document.getElementById('buscador');
const sugerencias = document.getElementById('sugerencias');

const paginas = [
  { nombre: 'Inicio', url: 'index.php' },
  { nombre: 'Nosotros', url: 'html/nosotros.php' },
  { nombre: 'Contacto', url: 'html/contacto.php' },
  { nombre: 'Productos', url: 'html/productos.php' },
];

// Función para obtener contenido de cada página
async function buscarEnPaginas(termino) {
  sugerencias.innerHTML = '';

  const resultados = await Promise.all(paginas.map(async (pagina) => {
    try {
      const res = await fetch(pagina.url);
      const texto = await res.text();

      // Crear un DOM virtual para buscar solo en el contenido visible
      const parser = new DOMParser();
      const doc = parser.parseFromString(texto, 'text/html');

      // Extraer texto visible de elementos específicos
      const contenidoVisible = Array.from(doc.querySelectorAll('h1, h2, p, a')).map(el => el.textContent).join(' ');

      if (contenidoVisible.toLowerCase().includes(termino.toLowerCase())) {
        return pagina;
      }
    } catch (e) {
      console.error('Error al buscar en:', pagina.url, e);
      return null;
    }
  }));

  const coincidencias = resultados.filter(p => p);
  coincidencias.forEach(pagina => {
    const li = document.createElement('li');
    li.innerHTML = `<a href="${pagina.url}">${pagina.nombre}</a>`;
    sugerencias.appendChild(li);
  });
}

// Escucha del input
buscador.addEventListener('input', () => {
  const termino = buscador.value.trim();
  if (termino.length > 1) {
    buscarEnPaginas(termino);
  } else {
    sugerencias.innerHTML = '';
  }
});

// Ocultar sugerencias al hacer clic fuera
document.addEventListener('click', (e) => {
  if (!buscador.contains(e.target) && !sugerencias.contains(e.target)) {
    sugerencias.innerHTML = '';
  }
});

/*MENU*/

(() => {
  const toggle = document.getElementById('menu-toggle');
  const nav = document.getElementById('nav');

  toggle.addEventListener('click', () => {
    nav.classList.toggle('active');
  });

})();
