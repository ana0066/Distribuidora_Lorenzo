document.addEventListener('DOMContentLoaded', () => {
    function show(index) {
        const slides = document.querySelectorAll('.slide');
        console.log('Índice:', index);
        console.log('Slides:', slides);

        if (slides.length === 0) {
            console.error('No se encontraron elementos con la clase .slide');
            return;
        }
        if (index >= 0 && index < slides.length) {
            slides[index].classList.add('active');
        } else {
            console.error('Índice fuera de rango:', index);
        }
    }

    window.moveSlide = function(index) {
        show(index);
    };
});

(() => {
  let current = 0;
  const slides = document.querySelectorAll('.slide');
  const total  = slides.length;

  function show(index) {
    const slides = document.querySelectorAll('.slide');
    console.log('Índice:', index);
    console.log('Slides:', slides);

    if (slides.length === 0) {
        console.error('No se encontraron elementos con la clase .slide');
        return;
    }
    if (index >= 0 && index < slides.length) {
        slides[index].classList.add('active');
    } else {
        console.error('Índice fuera de rango:', index);
    }
  }

  window.moveSlide = dir => {
    current = (current+dir+total)%total;
    show(current);
  };

  setInterval(()=> moveSlide(1),5000);
  show(current);
})();
