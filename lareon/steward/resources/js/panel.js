import './bootstrap.js'
import './general.js'
import './tools.js'
import {loader, logout} from "./general.js";


function singleArrayInput() {

    const fields = document.querySelectorAll('.oneFieldInput');
    const submitBtn = document.getElementById('totpFieldSubmit');

    if (!fields.length) return;

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
    const currentUrl = location.toString().replace(location.search, "")

    const endpoint = `${currentUrl}/send-otp`;
    const csrfToken = form.querySelector("[name = '_token']").value;
    const action = form.querySelector("[name = 'action']").value;
    const resultEl = document.querySelector('#resultMsg');

    if (!csrfToken) {
        console.error('CSRF token not found');
        return;
    }

    otpBox.addEventListener('click', async (e) => {
        const button = e.target.closest('button');

        if (resultEl) { resultEl.innerHTML = ''; }

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
                body: JSON.stringify({contactType, action})
            });

            clearTimeout(timeout);

            const data = await response.json();

            if (!response.ok) {
                let errors = [];
                if (data.errors) {
                    errors = Object.values(data.errors).flat();
                }

                if (!errors.length && data.message) {
                    errors.push(data.message);
                }

                throw errors;
            }

            resultEl.innerHTML = `<span class="text-green-600 bg-green-50 px-2 py-1">otp code has been sent successfully</span>`

            startOtpCooldown(button, originalHtml);


        } catch (error) {
            clearTimeout(timeout);
            console.error(error);

            if (resultEl) {

                const messages = Array.isArray(error)
                    ? error
                    : [error.message || 'Something went wrong'];

                resultEl.innerHTML = messages
                    .map(message => `<div class="text-red-600 bg-red-50 px-2 py-1">${message}</div>`)
                    .join('');

                resultEl.classList.remove('hidden');
            }
            button.disabled = false;
            button.innerHTML = originalHtml;
        }
    });
}


function startOtpCooldown(button, originalHtml, seconds = 120) {
    let remaining = seconds;

    const timer = setInterval(() => {

        button.innerHTML = `send again in <span>${remaining}s</span>`;

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
