import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['tab', 'panel']
    static values = {
        defaultTab: String
    }

    connect() {
        this.show(this.defaultTabValue || 'reservationsUpcoming')
    }

    switch(event) {
        this.show(event.currentTarget.dataset.tab)
    }

    show(tabName) {
        this.panelTargets.forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.panel !== tabName)
        })

        this.tabTargets.forEach((tab) => {
            tab.classList.toggle('tab-active', tab.dataset.tab === tabName)
        })
    }
}