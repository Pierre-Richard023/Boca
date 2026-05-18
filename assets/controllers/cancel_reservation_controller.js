import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static values = {
        url: String,
        token: String
    }

    static targets = [
        'status',
        'form',
        'reason',
        'openButton',
        'submitButton'
    ]

    toggleForm() {
        this.formTarget.classList.toggle('hidden')
    }

    hideForm() {
        this.formTarget.classList.add('hidden')
    }

    async submit(event) {
        event.preventDefault()

        this.submitButtonTarget.disabled = true
        const originalText = this.submitButtonTarget.textContent
        this.submitButtonTarget.textContent = 'Annulation...'

        try {
            const response = await fetch(this.urlValue, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                },
                body: new URLSearchParams({
                    _token: this.tokenValue,
                    reason: this.reasonTarget.value
                })
            })

            const data = await response.json()

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Erreur lors de l’annulation')
            }

            this.statusTarget.textContent = 'Annulée'
            this.statusTarget.classList.remove('bg-gold/20', 'text-gold')
            this.statusTarget.classList.add('bg-destructive/20', 'text-destructive')

            this.formTarget.remove()
            this.openButtonTarget.insertAdjacentHTML(
                'afterend',
                `<span class="text-xs px-2 py-1 uppercase tracking-wider bg-destructive/20 text-destructive">
                    Annulée
                </span>`
            )

            this.openButtonTarget.remove()
        } catch (error) {
            alert(error.message)
            this.submitButtonTarget.disabled = false
            this.submitButtonTarget.textContent = originalText
        }
    }

}