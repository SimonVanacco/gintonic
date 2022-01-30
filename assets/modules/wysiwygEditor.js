let WysiwygEditor = {
    init: function () {
        this.initEditors();
    },
    initEditors: function() {
        const elements = document.querySelectorAll('textarea.tinymce');
        if (elements.length > 0) {
            return import('./tinyMceImport.js')
                .then(({default: tinymce}) => {
                    tinymce.init({
                        selector: 'textarea.tinymce',
                        skin: "oxide-dark",
                        content_css: "dark"
                    });
                })
        }
    }
}

export default WysiwygEditor;