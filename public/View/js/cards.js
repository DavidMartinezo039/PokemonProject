let isScrolling = false;

window.addEventListener('scroll', function() {
    if (!isScrolling) {
        isScrolling = true;
        document.body.classList.add('no-hover');
    }

    clearTimeout(isScrolling);
    isScrolling = setTimeout(function() {
        document.body.classList.remove('no-hover');
    }, 200);
});

function openModal(cardElement) {
    document.getElementById("modalCardImage").src = cardElement.dataset.image;
    document.getElementById("modalCardName").innerText = cardElement.dataset.name;
    document.getElementById("modalCardRarity").innerText = cardElement.dataset.rarity;
    document.getElementById("modalCardSet").innerText = cardElement.dataset.set;

    // Generar enlace dinámico usando Blade
    document.getElementById('viewCardButton').href = `/cards/${cardElement.dataset.id}`;

    document.getElementById("cardModal").style.display = "flex";

    // Agregar evento de escape
    document.addEventListener("keydown", closeOnEscape);
}

function closeModal() {
    document.getElementById("cardModal").style.display = "none";
    document.removeEventListener("keydown", closeOnEscape);
}

function clickOutside(event) {
    if (event.target.id === "cardModal") {
        closeModal();
    }
}

function closeOnEscape(event) {
    if (event.key === "Escape") {
        closeModal();
    }
}
