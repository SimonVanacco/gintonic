import '@fortawesome/fontawesome-free/css/all.min.css';

import './styles/app.scss';

import 'bootstrap';

import './bootstrap';

import WysiwygEditor from "./modules/wysiwygEditor";
WysiwygEditor.init();

// Autosubmit of autocomplete
document.addEventListener("turbo:load", function() {
    document.querySelectorAll('[data-autosubmit="true"]').forEach(el => {
        el.addEventListener('autocomplete.change', (e) => {
            e.target.closest('form').requestSubmit();
        });
        el.addEventListener('change', (e) => {
            e.target.closest('form').requestSubmit();
        });
    });
})
