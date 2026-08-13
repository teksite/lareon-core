import {loader} from "../../../../steward/resources/js/general.js";

function schemaLoader() {
    const selectorEl = document.querySelector('select[data-schema-selector]');
    const viewEl = document.querySelector('[data-schema-view]');

    const model = document.querySelector('input[name="model"]')?.value ?? null;
    const modelId = document.querySelector('input[name="model_key"]')?.value ?? null;
    const csrfToken = document.querySelector('input[name="_token"]')?.value ?? null;

    if (!selectorEl || !viewEl || !csrfToken) return;

    const url = "/tkadmin/ajax/seo/schema/loader";

    let controller = null;
    let lastSchema = selectorEl.value;
    let requestId = 0;

    selectorEl.addEventListener('change', async (event) => {
        const schema = event.currentTarget.value;

        if (schema === lastSchema) return;

        lastSchema = schema;

        if (controller) controller.abort();


        controller = new AbortController();

        const currentRequestId = ++requestId;

        const basicView = viewEl.innerHTML;

        viewEl.innerHTML = loader;

        try {
            selectorEl.disabled=true;
            const response = await fetch(url, {
                method: "POST",

                headers: {
                    "Content-Type": "application/json",
                    "Accept": "text/html",
                    "X-CSRF-TOKEN": csrfToken,
                },

                body: JSON.stringify({schema, model, modelId,}),
                signal: controller.signal,
            });

            if (!response.ok) throw new Error(`Schema loader failed: ${response.status}`);

            const html = await response.text();

            console.log(html)
            if (currentRequestId !== requestId) return;

            viewEl.innerHTML = html;

        } catch (error) {

            if (error.name === "AbortError")  return;


            console.error("Schema loader error:", error);

            if (currentRequestId === requestId) {
                viewEl.innerHTML = basicView;

                viewEl.insertAdjacentHTML(
                    "beforeend",
                    "<p id='schemaError'>Error loading schema</p>"
                );
            }

        } finally {

            if (currentRequestId === requestId) controller = null;
            selectorEl.disabled=false;


        }
    });
}

schemaLoader();
