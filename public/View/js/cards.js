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
