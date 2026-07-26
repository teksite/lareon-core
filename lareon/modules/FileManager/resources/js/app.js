import {Standalone} from "./browser.min.js";


const picker = Standalone('.imageBtn',{
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
