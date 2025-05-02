let currentSlide = 0;
  const slides = document.querySelectorAll(".slide");

  function moveSlide(direction) {
    currentSlide += direction;
    if (currentSlide < 0) currentSlide = slides.length - 1;
    if (currentSlide >= slides.length) currentSlide = 0;

    document.querySelector(".slider").style.transform = `translateX(-${currentSlide * 100}%)`;
  }