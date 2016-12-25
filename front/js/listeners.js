const listeners = [
    () => {
        const toggle = document.getElementById('nav-toggle');
        if (toggle.classList.contains('animated')) { return; }

        const menu = document.getElementById('nav-menu');
        toggle.addEventListener('click', () => {
            menu.classList.toggle('is-active');
            toggle.classList.toggle('is-active');
        });

        toggle.classList.add('animated');
    }
];

listeners.map(fn => fn());
