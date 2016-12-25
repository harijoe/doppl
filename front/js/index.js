require("../scss/index.scss");

const toggle = document.getElementById('nav-toggle');
const menu = document.getElementById('nav-menu');
console.log(toggle);
console.log(menu);
toggle.addEventListener('click', () => {
    menu.classList.toggle('is-active');
    toggle.classList.toggle('is-active');
});


if (module.hot) {
    module.hot.accept();
}
