import {Controller} from '@hotwired/stimulus';

export default class extends Controller {

    static targets = ['button'];

    connect() {
        this.originalText = this.buttonTarget.innerHTML;
    }

    submit() {
        this.buttonTarget.innerHTML = "<i class='fas fa-spinner fa-spin'></i> ";
        this.buttonTarget.disabled = true;
    }

    end() {
        location.reload();
    }

}
