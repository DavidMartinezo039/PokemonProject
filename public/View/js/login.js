document.addEventListener('DOMContentLoaded', function() {
    const errorMessages = document.querySelectorAll('.error-message');
    const main = document.querySelector('.main');

    if (errorMessages.length > 0) {
        main.style.height = '600px';
        main.style.marginTop = '115px';
        main.style.marginBottom = '100px';
    }
});
