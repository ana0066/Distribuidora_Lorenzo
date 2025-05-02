const buscador = document.getElementById('buscador');
  const sugerencias = document.getElementById('sugerencias');

  const paginas = [
    { nombre: 'Inicio', url: 'index.php' },
    { nombre: 'Nosotros', url: 'html/nosotros.php' },
    { nombre: 'Contacto', url: 'html/contacto.php' },
    { nombre: 'Productos', url: 'html/productos.php' },
    { nombre: 'Iniciar sesión', url: 'php/login.php' },
    // Agrega más páginas según necesites
  ];

  // Función para obtener el contenido visible de cada página
  async function buscarEnPaginas(termino) {
    sugerencias.innerHTML = '';

    const resultados = await Promise.all(paginas.map(async (pagina) => {
      try {
        const res = await fetch(pagina.url);
        const texto = await res.text();
        
        // Crear un elemento temporal para procesar el HTML sin afectar el DOM
        const parser = new DOMParser();
        const doc = parser.parseFromString(texto, 'text/html');
        
        // Seleccionar solo los elementos de texto visibles
        const contenidoVisible = doc.body.textContent || doc.body.innerText;
        
        // Buscar si el término está presente en el texto visible
        const index = contenidoVisible.toLowerCase().indexOf(termino.toLowerCase());
        if (index !== -1) {
          // Resaltar el término en el texto visible
          const parteTexto = contenidoVisible.substring(index - 30, index + 30); // Extraer una parte alrededor de la palabra clave
          const textoResaltado = parteTexto.replace(new RegExp(termino, 'gi'), (match) => `<span class="highlight">${match}</span>`);
          
          return { nombre: pagina.nombre, url: pagina.url, textoResaltado };
        }
      } catch (e) {
        return null;
      }
    }));

    const coincidencias = resultados.filter(p => p);

    // Si no hay resultados, mostrar el mensaje de "No se encontraron resultados"
    if (coincidencias.length === 0) {
      sugerencias.innerHTML = '<li>No se encontraron resultados</li>';
    } else {
      // Mostrar las sugerencias encontradas
      coincidencias.forEach(pagina => {
        const li = document.createElement('li');
        li.innerHTML = `
          <strong>${pagina.nombre}</strong>: <a href="${pagina.url}">${pagina.textoResaltado}</a>
        `;
        sugerencias.appendChild(li);
      });
    }
  }

  // Escucha del input
  buscador.addEventListener('input', () => {
    const termino = buscador.value.trim();
    if (termino.length > 2) {
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
