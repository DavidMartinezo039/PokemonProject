window.addEventListener('DOMContentLoaded', event => {

    const masthead = document.querySelector('.masthead');
    const sidebar = document.querySelector('.sidebar'); // Ajusta esto según tu clase para la barra lateral

    if (sidebar && sidebar.classList.contains('open')) {  // Si la barra lateral está abierta
        masthead.style.width = 'calc(100vw - 250px)'; // Ajustar el ancho
    } else {
        masthead.style.width = '100vw'; // Si la barra lateral está cerrada, usa todo el ancho
    }
    // Navbar shrink function
    var navbarShrink = function () {
        const navbarCollapsible = document.body.querySelector('#mainNav');
        if (!navbarCollapsible) {
            return;
        }
        if (window.scrollY === 0) {
            navbarCollapsible.classList.remove('navbar-shrink'); // Se vuelve transparente
        } else {
            navbarCollapsible.classList.add('navbar-shrink'); // Se oscurece
        }
    };

    // Ejecuta la función al cargar la página
    navbarShrink();

    // Escucha el scroll para cambiar el navbar
    document.addEventListener('scroll', navbarShrink);

    //  Activar Bootstrap scrollspy en la navegación principal
    const mainNav = document.body.querySelector('#mainNav');
    if (mainNav) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#mainNav',
            rootMargin: '0px 0px -40%',
        });
    };

    // Cierra el menú de navegación al hacer clic en un enlace en modo responsive
    const navbarToggler = document.body.querySelector('.navbar-toggler');
    const responsiveNavItems = [].slice.call(
        document.querySelectorAll('#navbarResponsive .nav-link')
    );
    responsiveNavItems.map(function (responsiveNavItem) {
        responsiveNavItem.addEventListener('click', () => {
            if (window.getComputedStyle(navbarToggler).display !== 'none') {
                navbarToggler.click();
            }
        });
    });

});
