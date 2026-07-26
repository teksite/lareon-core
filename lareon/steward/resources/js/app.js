import './bootstrap.js'
import './general.js'
import './tools.js'
import {logout} from "./general.js";
import {Standalone} from "./browser.min.js";

function initSingleImageSelector(selector) {

    const singleIdBtnPicker = document.querySelectorAll(selector);

    if (singleIdBtnPicker.length) {
        singleIdBtnPicker.forEach(btnEk => {
            try {
                const picker = Standalone(selector, {
                    trigger: selector,
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
                            expect: "object"
                        }
                    }
                }).on(files => {
                    console.log(files)
                });
            } catch (error) {
                console.error(error);
            }
        });
    }

}

document.addEventListener('DOMContentLoaded', function () {
    logout();
    initSingleImageSelector('.imageBtn');
});
