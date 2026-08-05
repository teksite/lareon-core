import './bootstrap.js'
import './tools.js'
import {initInlineSelectBox, logout, runObserver, SlugMaker} from "./general.js";
import {Standalone} from "./browser.min.js";


function bindSingleImageButton(button) {

    const container = button.closest('[data-single-image]');

    const btnId = button.id;

    if (!container || !btnId) return;

    const type = button.dataset.type ?? 'object';
    const preview = container.querySelector('img');
    const input = container.querySelector('input');
    const deleteButton = container.querySelector('[data-delete-btn]');
    const placeholder = preview?.dataset.placehoder;

    deleteButton?.addEventListener('click', e => {
        e.preventDefault();

        if (preview) preview.src = placeholder ?? '';

        input.value = '';
    });
    Standalone(`#${btnId}`, {
        trigger: `#${btnId}`,
        config: {
            load: {
                types: ["image"],
                disks: ["public"],
            },
            upload: {
                allowedMimes: ['image/jpeg'],
                allowedDisks: ['public', 'local', 's3']
            },
            selection: {
                mode: "single",
                expect: type
            }
        }
    }).on(files => {
        if (preview) preview.src = files.url

        input.value = files.id
    });

}

function initSingleImageSelector() {

    document.querySelectorAll('.imageBtn').forEach(bindSingleImageButton);
}


document.addEventListener('DOMContentLoaded', function () {

    logout();

    initSingleImageSelector('.imageBtn');

    new SlugMaker('-').attachToInput();

    initInlineSelectBox();


    runObserver();
});
