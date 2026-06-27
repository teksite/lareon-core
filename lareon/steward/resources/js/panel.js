import './bootstrap.js'
import './general.js'
import './tools.js'
import {logout} from "./general.js";
import initiateOTP from "./tools.js";

import { Passkeys } from '@laravel/passkeys';

window.Passkeys = Passkeys;
window.dispatchEvent(new CustomEvent('passkeys:ready'));

class PasskeyManager {

    constructor() {

        this.container=document.getElementById('passkey-manager');
        if(!this.container) return;

        this.optionUrl=this.container.dataset.optionUrl;
        this.storeUrl=this.container.dataset.storeUrl;

        this.form=document.getElementById('passkey-form');
        this.input=document.getElementById('passkey-name');

        this.submitBtn=document.getElementById(
            'register-passkey'
        );

        this.cancelBtn=document.getElementById(
            'cancel-passkey'
        );

        this.error=document.getElementById(
            'passkey-error'
        );

        this.loading=false;

        this.init();
    }


    init() {

        this.prefillDeviceName();

        this.form.addEventListener(
            'submit',
            this.register.bind(this)
        );

        this.cancelBtn.addEventListener(
            'click',
            this.reset.bind(this)
        );
    }


    prefillDeviceName() {

        const ua=navigator.userAgent;

        const browser=
            /Chrome/i.test(ua) ? 'Chrome'
                : /Firefox/i.test(ua) ? 'Firefox'
                    : /Safari/i.test(ua) ? 'Safari'
                        : '';

        const os=
            /Windows/i.test(ua) ? 'Windows'
                : /Mac/i.test(ua) ? 'Mac'
                    : /Android/i.test(ua) ? 'Android'
                        : /iPhone/i.test(ua) ? 'iPhone'
                            : '';

        this.input.value=
            [browser,os]
                .filter(Boolean)
                .join(' on ')
            || 'My Device';
    }


    async register(e){

        e.preventDefault();

        if(this.loading) return;

        const name=this.input.value.trim();

        if(!name) return;


        this.loading=true;

        this.hideError();

        this.submitBtn.disabled=true;


        try{

            await window.Passkeys.register({

                name,

                routes:{
                    options:this.optionUrl,
                    submit:this.storeUrl
                }

            });

            location.reload();

        }
        catch(e){

            if(
                e?.name!=='UserCancelledError'
            ){
                this.showError(
                    e?.message ??
                    'Registration failed'
                );
            }

        }
        finally{

            this.loading=false;

            this.submitBtn.disabled=false;
        }

    }


    showError(message){

        this.error.textContent=message;

        this.error.classList.remove(
            'hidden'
        );
    }


    hideError(){

        this.error.textContent='';

        this.error.classList.add(
            'hidden'
        );
    }


    reset(){

        this.form.reset();

        this.hideError();

        document.dispatchEvent(
            new CustomEvent(
                'close-modal'
            )
        );
    }

}

class PasskeyLogin {

    constructor() {

        this.container=document.getElementById(
            'passkey-login'
        );

        if(!this.container) return;

        this.button=document.getElementById(
            'passkey-login-button'
        );

        this.label=document.getElementById(
            'passkey-login-label'
        );

        this.error=document.getElementById(
            'passkey-login-error'
        );

        this.wrapper=document.getElementById(
            'passkey-login-wrapper'
        );

        this.optionsUrl=
            this.container.dataset.optionsUrl;

        this.submitUrl=
            this.container.dataset.submitUrl;

        this.redirectUrl=
            this.container.dataset.redirectUrl;

        this.loading=false;

        this.init();

    }

    init() {

        if(
            !window.Passkeys?.isSupported()
        ){
            return;
        }

        this.wrapper.classList.remove(
            'hidden'
        );

        this.button.addEventListener(
            'click',
            ()=>this.verify()
        );

    }

    async verify(){

        if(this.loading) return;

        this.loading=true;

        this.hideError();

        this.button.disabled=true;

        this.label.innerText=
            'Authenticating...';


        try{

            const response=
                await window.Passkeys.verify({

                    routes:{
                        options:this.optionsUrl,
                        submit:this.submitUrl
                    }

                });


            /*
             package ممکن است redirect کند
             اگر نکرد:
            */

            if(response?.redirect){

                window.location.href=
                    response.redirect;

                return;
            }


            window.location.href=
                this.redirectUrl;

        }

        catch(e){

            if(
                e?.name!=='UserCancelledError'
            ){

                this.showError(
                    e?.message ??
                    'Authentication failed'
                );

            }

        }

        finally{

            this.loading=false;

            this.button.disabled=false;

            this.label.innerText=
                'Sign in with a passkey';

        }

    }

    showError(message){

        this.error.innerText=
            message;

        this.error.classList.remove(
            'hidden'
        );

    }

    hideError(){

        this.error.innerText='';

        this.error.classList.add(
            'hidden'
        );

    }

}



document.addEventListener('DOMContentLoaded', function () {
    logout();
    initiateOTP();
    new PasskeyManager();
    new PasskeyLogin();
});
