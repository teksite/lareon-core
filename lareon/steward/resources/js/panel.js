import './bootstrap.js'
import './general.js'
import './tools.js'
import {logout} from "./general.js";
import initiateOTP from "./tools.js";

import { Passkeys } from '@laravel/passkeys';

window.Passkeys = Passkeys;
window.dispatchEvent(new CustomEvent('passkeys:ready'));




document.addEventListener('DOMContentLoaded', function () {
    logout();
    initiateOTP()
});
