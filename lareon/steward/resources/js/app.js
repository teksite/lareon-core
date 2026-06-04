import './bootstrap.js'
import './general.js'
import './tools.js'


function loadAjaxForm() {
    const formEls = document.querySelectorAll('form.formAction[data-ajax]');
    if (!formEls.length) return;

    formEls.forEach(formEl => {
        formEl.addEventListener('submit', e => {
            e.preventDefault();
        });
    });
}
