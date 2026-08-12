import {loader} from "../../../../steward/resources/js/general.js";

function schemaLoader() {
    const selectorEl = document.querySelector('select[data-schema-selector]');
    const viewEl = document.querySelector('[data-schema-view]');

    if (!viewEl || !selectorEl) return;

    selectorEl.addEventListener('change', async e => {
        const errorSchemaEl = viewEl.querySelector('#schemaError');
        errorSchemaEl?.remove();

        const basicView = viewEl.innerHTML;

        const schema = e.currentTarget.value;
        viewEl.innerHTML = loader;

        try {
            const url = "https://example.org/products.json";
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`Response status: ${response.status}`);
            }
        } catch (error) {
            console.error(error);
            viewEl.innerHTML = basicView;
            viewEl.insertAdjacentHTML('beforeend', "<p id='schemaError'>error in loadding Schema</p>");
        }
    })

}

schemaLoader();
