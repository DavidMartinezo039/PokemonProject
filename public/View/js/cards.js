document.querySelectorAll('.card').forEach(card => {
    card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left; // Coordenada X dentro de la tarjeta
        const y = e.clientY - rect.top; // Coordenada Y dentro de la tarjeta
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;

        // Cálculo de la rotación en función de la posición del cursor dentro de la carta
        const rotateX = ((y - centerY) / centerY) * 15; // Ángulo de rotación en X (vertical)
        const rotateY = ((x - centerX) / centerX) * -15; // Ángulo de rotación en Y (horizontal)

        // Aplicar la rotación 3D a la carta
        card.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`; // Aumentamos el tamaño al hacer hover
    });

    card.addEventListener('mouseleave', () => {
        card.style.transform = 'rotateX(0) rotateY(0) scale(1)'; // Restaura la rotación y tamaño original
    });
});
