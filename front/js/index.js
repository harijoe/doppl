import '../scss/index.scss';
import './listeners';
import 'particles.js';

particlesJS.load('particles-js', window.location.origin + '/config/particles.json', function() {
    console.log('callback - particles.js config loaded');
});

if (module.hot) {
    module.hot.accept();
}
