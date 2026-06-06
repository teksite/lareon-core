import './bootstrap.js'
import './general.js'
import './tools.js'
import {loader, logout} from "./general.js";


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

            if (!['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key) && !/^\d$/.test(e.key)) {
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


function requestSendOTP() {
    const otpBox = document.getElementById('sendOtp');
    const form = document.getElementById('sendOtpGuest');

    if (!otpBox || !form) return;

    const endpoint = `${form.action}/send`;
    const csrfToken= form.querySelector("[name = '_token']").value;

    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    otpBox.addEventListener('click', async (e) => {
        const button = e.target.closest('button');

        if (!button) return;

        let contactType = null;

        switch (button.id) {
            case 'sendOtpViaEmail':
                contactType = 'email';
                break;

            case 'sendOtpViaSMS':
                contactType = 'phone';
                break;
        }

        if (!contactType) return;

        const originalHtml = button.innerHTML;

        const controller = new AbortController();

        const timeout = setTimeout(() => {
            controller.abort();
        }, 15000);

        try {
            button.disabled = true;

            button.innerHTML = ` ${loader} <span>Sending...</span> `;

            const response = await fetch(endpoint, {
                method: 'POST',
                signal: controller.signal,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ contactType })
            });

            clearTimeout(timeout);

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to send OTP');
            }


            startOtpCooldown(button, originalHtml);

        } catch (error) {
            clearTimeout(timeout);
            console.error(error);

            button.disabled = false;
            button.innerHTML = originalHtml;
        }
    });
}


function startOtpCooldown(button, originalHtml, seconds = 120) {
    let remaining = seconds;

    const timer = setInterval(() => {

        button.innerHTML = `
            ${loader}
            <span>${remaining}s</span>
        `;

        remaining--;

        if (remaining < 0) {
            clearInterval(timer);

            button.disabled = false;
            button.innerHTML = originalHtml;
        }

    }, 1000);
}

document.addEventListener('DOMContentLoaded', function () {
    logout();
    singleArrayInput();
    requestSendOTP();
});
