document.addEventListener('DOMContentLoaded', () => {
    let currentIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const totalSlides = slides.length;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
        });

        slides[index].classList.add('active');
    }

    function moveSlide(n) {
        currentIndex = (currentIndex + n + totalSlides) % totalSlides;
        showSlide(currentIndex);
    }

    // Botones de flecha
    document.querySelector('.prev').addEventListener('click', () => moveSlide(-1));
    document.querySelector('.next').addEventListener('click', () => moveSlide(1));

    // Movimiento automático
    setInterval(() => {
        moveSlide(1);
    }, 5000);

    // Mostrar primera slide
    showSlide(currentIndex);
});
