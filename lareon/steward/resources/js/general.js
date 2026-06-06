export function logout (){
    const logoutButtonEls = document.querySelectorAll('.logoutBtn');
    const logoutFormEl = document.getElementById('logoutForm');
    if (logoutButtonEls.length === 0 || !logoutFormEl) return;

    logoutButtonEls.forEach(btn=>{
        try {
            btn.addEventListener('click',e=>{
                e.preventDefault();
                logoutFormEl.submit();
            })
        }catch (err){
            console.error(err)
        }
    });
}


function loadAjaxForm() {
    const formEls = document.querySelectorAll('form.formAction[data-ajax]');
    if (!formEls.length) return;

    formEls.forEach(formEl => {
        formEl.addEventListener('submit', e => {
            e.preventDefault();
        });
    });
}


export const loader = `<svg class="mr-3 -ml-1 size-5 animate-spin text-white stroke-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10"  stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

