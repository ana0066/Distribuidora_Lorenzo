(() => {
  let current = 0;
  const slides = document.querySelectorAll('.slide');
  const total  = slides.length;

  function show(n){
    slides.forEach(s=>s.classList.remove('active'));
    slides[n].classList.add('active');
    document.getElementById('slider')
      .style.transform = `translateX(-${n*100}%)`;
  }

  window.moveSlide = dir => {
    current = (current+dir+total)%total;
    show(current);
  };

  setInterval(()=> moveSlide(1),5000);
  show(current);
})();
