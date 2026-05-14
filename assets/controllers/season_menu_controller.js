import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['tab', 'panel'];
    static values = {
        activeId: String,
    };

    connect() {
        const hash = window.location.hash.replace('#', '');
        const initialId = hash || this.activeIdValue || this.tabTargets[0]?.dataset.id;

        if (initialId) {
            this.show(initialId);
        }
    }

    select(event) {
        event.preventDefault();

        const id = event.currentTarget.dataset.id;
        this.show(id);
        history.replaceState(null, '', `#${id}`);
    }

    show(id) {
        this.tabTargets.forEach((tab) => {
            const active = tab.dataset.id === id;

            tab.setAttribute('aria-selected', active ? 'true' : 'false');

            tab.classList.toggle('bg-gold', active);
            tab.classList.toggle('text-background', active);
            tab.classList.toggle('border-gold', active);

            tab.classList.toggle('border-border/60', !active);
            tab.classList.toggle('text-foreground', !active);
            tab.classList.toggle('hover:border-gold', !active);
            tab.classList.toggle('hover:text-gold', !active);
        });

        this.panelTargets.forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.id !== id);
        });

        this.activeIdValue = id;
    }
}