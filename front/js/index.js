import '../scss/index.scss';
import './listeners';
import 'particles.js';
import config from '../json/particles.json';

window.onload = () => {
    particlesJS('particles-js', config);
    const particles = document.getElementsByClassName('particles');
    [...particles].forEach(el => el.classList.add('show'));
};

if (module.hot) {
    module.hot.accept();
}
