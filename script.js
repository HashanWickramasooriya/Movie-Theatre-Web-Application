const header = document.querySelector('header');

function fixedNavbar() {
    header.classList.toggle('scrolled', window.pageYoffset > 0)
}
fixedNavbar();
window.addEventListener('scroll', fixedNavbar);

let menu = document.querySelector('#menu-btn');
let userBtn = document.querySelector('#user-btn');

menu.addEventListener('click', function() {
    let nav = document.querySelector('.navbar');
    nav.classList.toggle('active');
})

userBtn.addEventListener('click', function() {
    let userBox = document.querySelector('.user-box');
    userBox.classList.toggle('active');
})

const closeBtn = document.querySelector('#close-form');

if (closeBtn) {
    closeBtn.addEventListener('click', () => {
        const updateContainer = document.querySelector('.update-container');
        if (updateContainer) updateContainer.style.display = 'none';
    });
}