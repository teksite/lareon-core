import './bootstrap.js'
import './general.js'
import './tools.js'
import {logout} from "./general.js";


const picker = new StandaloneButton('#imageBtn',{
    trigger:"#imageBtn",
    config:{
        load:{
            types:["image"],
            disks:["public"],
        },
        selection:{
            mode:"multi",
            expect:"object"
        }
    }
}).on(files => {
    console.log(files);
});


document.addEventListener('DOMContentLoaded', function () {
    logout();
});
