import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        value: Boolean,
        payload: String,
    }

    connect() {
        this.element.addEventListener('click', () => {
            this.valueValue = !this.valueValue;
            this.toggleValue();
        });
    }

    valueValueChanged() {
        this.htmlIconSet();
    }

    htmlIconSet() {
        if (this.valueValue) {
            this.element.innerHTML = "<i class='fas fa-check text-success'></i>";
        } else {
            this.element.innerHTML = "<i class='fas fa-times text-danger'></i>";
        }
    }

    toggleValue() {
        fetch('/utils/boolean_toggle', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({'payload': this.payloadValue})
        }).catch(() => {
            // An error occured, reflect that the value did not change
            this.valueValue = !this.valueValue;
        })
    }

}
