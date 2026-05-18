import { Controller } from '@hotwired/stimulus';

const WEEKLY_SLOTS = {
    0: ["12:00", "12:30", "13:00", "19:00", "19:30", "20:00"],
    1: [],
    2: ["19:00", "19:30", "20:00", "20:30", "21:00"],
    3: ["19:00", "19:30", "20:00", "20:30", "21:00"],
    4: ["19:00", "19:30", "20:00", "20:30", "21:00"],
    5: ["19:00", "19:30", "20:00", "20:30", "21:00", "21:30", "22:00"],
    6: ["19:00", "19:30", "20:00", "20:30", "21:00", "21:30", "22:00"],
};

export default class extends Controller {
    static targets = ['date', 'slots', 'timeInput', 'submitBtn'];

    connect() {
        const today = new Date().toISOString().split('T')[0];
        if (!this.dateTarget.value) {
            this.dateTarget.value = today;
        }
        this.dateTarget.min = today;

        this.state = {
            reservation_date: this.dateTarget.value,
            reservation_time: this.timeInputTarget.value || '',
        };

        this.dateTarget.addEventListener('change', (e) => {
            this.state.reservation_date = e.target.value;
            this.state.reservation_time = '';
            this.syncHiddenTime();
            this.renderSlots();
        });

        this.renderSlots();
    }

    renderSlots() {
        const date = this.state.reservation_date;
        this.slotsTarget.innerHTML = '';
        if (!date) return;

        const wd = new Date(date + 'T00:00:00').getDay();
        const slots = WEEKLY_SLOTS[wd] || [];

        if (slots.length === 0) {
            this.slotsTarget.innerHTML =
                '<p class="text-sm text-muted-foreground italic col-span-full">Aucun service ce jour. Choisissez une autre date.</p>';
            this.state.reservation_time = '';
            this.syncHiddenTime();
            return;
        }

        slots.forEach((t) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'slot' + (this.state.reservation_time === t ? ' selected' : '');
            btn.textContent = t;
            btn.addEventListener('click', () => {
                this.state.reservation_time = t;
                this.syncHiddenTime();
                this.renderSlots();
            });
            this.slotsTarget.appendChild(btn);
        });

        if (!slots.includes(this.state.reservation_time)) {
            this.state.reservation_time = slots[0];
            this.syncHiddenTime();
            this.renderSlots();
        }
    }

    syncHiddenTime() {
        this.timeInputTarget.value = this.state.reservation_time || '';
    }
}