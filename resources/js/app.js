// Mobile navigation toggle.
document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-menu-toggle]');

    if (!toggle) {
        return;
    }

    const menu = document.getElementById(toggle.getAttribute('aria-controls'));
    const isOpen = toggle.getAttribute('aria-expanded') === 'true';

    toggle.setAttribute('aria-expanded', String(!isOpen));
    menu?.classList.toggle('hidden', isOpen);
});
