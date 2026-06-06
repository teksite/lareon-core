import './bootstrap.js'
import './general.js'
import './tools.js'


function singleArrayInput() {

    const fields = document.querySelectorAll('.oneFieldInput');
    const submitBtn = document.getElementById('totpFieldSubmit');

    fields.forEach((field, index) => {
        field.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');

            if (!value) {
                e.target.value = '';
                return;
            }

            e.target.value = value[0];

            if (index < fields.length - 1) {
                fields[index + 1].focus();
            }
        });

        // Backspace
        field.addEventListener('keydown', (e) => {

            if (e.key === 'Backspace' && field.value === '') {

                if (index > 0) {
                    fields[index - 1].focus();
                    fields[index - 1].select();
                }
            }

            if (!['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key) &&!/^\d$/.test(e.key)) {
                e.preventDefault();
            }
        });

        field.addEventListener('focus', () => {
            field.select();
        });
    });

    fields[0].addEventListener('paste', (e) => {

        e.preventDefault();

        const pasted = e.clipboardData
            .getData('text')
            .replace(/\D/g, '')
            .slice(0, fields.length);

        pasted.split('').forEach((char, index) => {
            fields[index].value = char;
        });

        const nextField = fields[Math.min(pasted.length, fields.length - 1)];
        nextField.focus();

    });

    function checkCompleted() {

        const completed = [...fields].every(
            field => field.value.length === 1
        );

        if (completed) {
            submitBtn.click();

            // یا:
            // submitBtn.closest('form').submit();
        }
    }
}

document.addEventListener('DOMContentLoaded',function (){
    singleArrayInput();
});
