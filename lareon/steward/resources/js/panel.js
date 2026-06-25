import './bootstrap.js'
import './general.js'
import './tools.js'
import {logout} from "./general.js";
import initiateOTP from "./tools.js";



document.addEventListener('DOMContentLoaded', function () {
    logout();
    initiateOTP()
});
