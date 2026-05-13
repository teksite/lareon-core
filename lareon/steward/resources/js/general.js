function logout (){
    const logoutButtonEls = document.querySelectorAll('.logoutBtn');
    const logoutFormEl = document.getElementById('formId');
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




document.addEventListener("DOMContentLoaded", function (event) {
   logout();
});
