import '@fortawesome/fontawesome-free/css/all.min.css';

import './styles/app.scss';

import 'bootstrap';

import './bootstrap';

import WysiwygEditor from "./modules/wysiwygEditor";
WysiwygEditor.init();

// Autosubmit of autocomplete
document.addEventListener("turbo:load", function() {
    document.querySelectorAll('.autocomplete-wrapper').forEach(el => {
        el.addEventListener('autocomplete.change', (e) => {
            if (e.target.dataset.autosubmit && e.target.dataset.autosubmit === 'true') {
                e.target.closest('form').submit();
            }
        })
    });
})
