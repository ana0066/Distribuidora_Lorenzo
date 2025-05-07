// ===== Buscador =====
(() => {
  const inputBuscador = document.getElementById('buscador');
  const sugerencias = document.getElementById('sugerencias');
  const paginas = [
    { nombre: 'Inicio', url: '../html/index.php' },
    { nombre: 'Nosotros', url: '../html/nosotros.php' },
    { nombre: 'Contacto', url: '../html/contacto.php' },
    { nombre: 'Productos', url: '../html/productos.php' },
  ];

  async function buscar(term) {
    sugerencias.innerHTML = '';
    if (term.length < 1) return;

    const results = await Promise.all(
      paginas.map(async (p) => {
        try {
          const res = await fetch(p.url);
          const text = await res.text();
          const doc = new DOMParser().parseFromString(text, 'text/html');
          const visible = Array.from(doc.querySelectorAll('h1,h2,h3,h4,p,a,button'))
            .map((el) => el.textContent)
            .join(' ');

          // Busca palabras completas que contengan el término
          const re = new RegExp(`\\b\\w*${term}\\w*\\b`, 'gi'); // Encuentra palabras completas que contengan el término
          const matches = [...visible.matchAll(re)];
          if (matches.length > 0) {
            return { pagina: p, coincidencias: matches };
          }
        } catch {
          return null;
        }
      })
    );

    const filteredResults = results.filter((r) => r);

    if (filteredResults.length === 0) {
      // Si no hay coincidencias, muestra un mensaje
      const li = document.createElement('li');
      li.textContent = 'No se han encontrado coincidencias';
      sugerencias.appendChild(li);
    } else {
      // Muestra las coincidencias encontradas
      filteredResults.forEach(({ pagina, coincidencias }) => {
        const li = document.createElement('li');
        li.innerHTML = `<a href="${pagina.url}">${pagina.nombre}</a>`;
        coincidencias.forEach(([match]) => {
          // Resalta la coincidencia dentro de la palabra completa
          const highlighted = match.replace(
            new RegExp(term, 'gi'),
            (match) => `<span class="highlight">${match}</span>`
          );
          const p = document.createElement('p');
          p.innerHTML = highlighted;
          li.appendChild(p);
        });
        sugerencias.appendChild(li);
      });
    }
  }

  inputBuscador.addEventListener('input', (e) => buscar(e.target.value.trim()));
  document.addEventListener('click', (e) => {
    if (!inputBuscador.contains(e.target) && !sugerencias.contains(e.target))
      sugerencias.innerHTML = '';
  });
})();

// ===== Menu =====
(function(){
  const btnMenu = document.getElementById('menu-toggle');
  const nav     = document.getElementById('nav');
  btnMenu.addEventListener('click', ()=> {
    nav.classList.toggle('active');
  });
})();

