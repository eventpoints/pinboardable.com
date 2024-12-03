import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        id: String
    }

    connect() {
        const pin = document.getElementById(`pin_${this.idValue}`);
        if (pin) {
            pin.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}
