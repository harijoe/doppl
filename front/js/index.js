import '../scss/index.scss';
import './listeners';
import 'particles.js';

particlesJS.load('particles-js', window.location.origin + '/config/particles.json', function() {
    const particles = document.getElementsByClassName('particles');
    console.log(particles);
    [...particles].forEach(el => el.classList.add('show'));
});

if (module.hot) {
    module.hot.accept();
}
