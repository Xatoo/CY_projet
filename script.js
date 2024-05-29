// Attend que le DOM soit entièrement chargé
document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne tous les éléments avec la classe 'carousel'
    const carousels = document.querySelectorAll('.carousel');

    // Pour chaque carousel trouvé
    carousels.forEach(carousel => {
        const container = carousel.querySelector('.carousel-container');
        const cards = carousel.querySelectorAll('.card-conseils');
        const dots = carousel.querySelectorAll('.nav-dot');
        let currentIndex = 0;
        const cardWidth = cards[0].offsetWidth; 
        const cardMargin = 10;

        // Fonction pour mettre à jour le carousel
        function updateCarousel() {
            const newPosition = -(currentIndex * (cardWidth + cardMargin));
            container.style.transform = `translateX(${newPosition}px)`;
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentIndex].classList.add('active');
        }

        // Pour chaque point de navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                currentIndex = index;
                // Met à jour le carousel
                updateCarousel();
            });
        });

        // Met à jour le carousel au chargement de la page
        updateCarousel();
    });
});

// Fonction pour mettre à jour le compte des caractères dans un textarea
function updateCharCount(textareaId, charCountId) {
    // Sélectionne le textarea et le compteur de caractères
    var textarea = document.getElementById(textareaId);
    var charCount = document.getElementById(charCountId);
    // Met à jour le texte du compteur avec le nombre actuel de caractères et le nombre maximal autorisé
    charCount.textContent = textarea.value.length + ' / ' + textarea.getAttribute('maxlength') + ' caractères';
}