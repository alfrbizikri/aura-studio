const navbarToggle = document.getElementById('navbarToggle');
const navbarMenu = document.getElementById('navbarMenu');
const navbarActions = document.querySelector('.navbar-actions');

if (navbarToggle) {
    navbarToggle.addEventListener('click', function () {
        navbarMenu.classList.toggle('active');
        navbarActions.classList.toggle('active');
    });
}