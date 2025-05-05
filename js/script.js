// ===== Buscador =====
(() => {
  const inputBuscador = document.getElementById('buscador');
  const sugerencias   = document.getElementById('sugerencias');
  const paginas = [
    {nombre:'Inicio', url:'../html/index.php'},
    {nombre:'Nosotros', url:'../html/nosotros.php'},
    {nombre:'Contacto', url:'../html/contacto.php'},
    {nombre:'Productos', url:'../html/productos.php'},
  ];

  async function buscar(term) {
    sugerencias.innerHTML = '';
    if(term.length<2) return;
    const results = await Promise.all(paginas.map(async p=>{
      try {
        const res  = await fetch(p.url);
        const text = await res.text();
        const doc  = new DOMParser().parseFromString(text,'text/html');
        const visible = Array.from(doc.querySelectorAll('h1,h2,h3,p,a,button'))
                          .map(el=>el.textContent).join(' ');
        const re = new RegExp(`(?:\\S+\\s){0,5}\\b${term}\\b(?:\\s\\S+){0,5}`,'gi');
        const m = visible.match(re);
        if(m) return {pagina:p, coincidencias:m};
      } catch {return null;}
    }));
    results.filter(r=>r).forEach(({pagina,coincidencias})=>{
      const li= document.createElement('li');
      li.innerHTML= `<a href="${pagina.url}">${pagina.nombre}</a>
                     <p>${coincidencias.join(' ... ')}</p>`;
      sugerencias.appendChild(li);
    });
  }

  inputBuscador.addEventListener('input', e=>buscar(e.target.value.trim()));
  document.addEventListener('click', e=>{
    if(!inputBuscador.contains(e.target)&&!sugerencias.contains(e.target))
      sugerencias.innerHTML='';
  });
})();

// ===== Menu =====
(() => {
  const btn = document.getElementById('menu-toggle');
  const nav = document.getElementById('nav');
  btn.addEventListener('click', ()=>nav.classList.toggle('active'));
})();
