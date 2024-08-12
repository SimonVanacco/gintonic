import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    connect() {
    }

    checkboxChanged(event) {
        const parentItem = event.target.closest('.shopping-list-item');
        if (!parentItem) {
            return;
        }

        const payload = parentItem.dataset.payload;

        // Value = if item has to be bought
        // If item is checked (true) then it has already been bought so value = false
        const value = !event.target.checked;

        this.toggleDisplay(parentItem);
        this.setValue(payload, value).catch(() => {
            this.toggleDisplay(parentItem);
        });
    }

    setValue(payload, value) {
        return fetch('/utils/boolean_set', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'payload': payload,
                'value': value
            })
        });
    }

    toggleDisplay(parentItem) {
        parentItem.querySelector('label').classList.toggle('text-decoration-line-through');
    }

}
