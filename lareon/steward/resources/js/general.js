import Sortable from "sortablejs";
import TomSelect from "tom-select";

let caches= new Set();

export const loader = `<svg class="mr-3 -ml-1 size-5 animate-spin text-white stroke-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10"  stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

export function logout() {
    const logoutButtonEls = document.querySelectorAll('.logoutBtn');
    const logoutFormEl = document.getElementById('logoutForm');
    if (logoutButtonEls.length === 0 || !logoutFormEl) return;

    logoutButtonEls.forEach(btn => {
        try {
            btn.addEventListener('click', e => {
                e.preventDefault();
                logoutFormEl.submit();
            })
        } catch (err) {
            console.error(err)
        }
    });
}


//     Slug maker and Changer     //
export class SlugMaker {
    constructor(separator = '-') {
        this.separator = separator;
        this.farsiToEnglishNumbers = {
            '۰': '0', '۱': '1', '۲': '2', '۳': '3', '۴': '4',
            '۵': '5', '۶': '6', '۷': '7', '۸': '8', '۹': '9',
            '٠': '0', '١': '1', '٢': '2', '٣': '3', '٤': '4',
            '٥': '5', '٦': '6', '٧': '7', '٨': '8', '٩': '9'
        };
        this.arabicToFarsiLetters = {
            'ي': 'ی', 'ك': 'ک', 'ة': 'ه', 'أ': 'ا', 'إ': 'ا', 'ؤ': 'و', 'ئ': 'ی', 'ى': 'ی'
        };
    }

    normalizeNumbers(str) {
        return str.replace(/[۰-۹٠-٩]/g, num => this.farsiToEnglishNumbers[num] || num);
    }

    normalizeArabicLetters(str) {
        return str.replace(/[يكةأإؤئى]/g, char => this.arabicToFarsiLetters[char] || char);
    }

    normalizeAccents(str) {
        let map = {
            a: /[áàảạãăắằẳẵặâấầẩẫậ]/gi,
            e: /[éèẻẽẹêếềểễệ]/gi,
            i: /[iíìỉĩị]/gi,
            o: /[óòỏõọôốồổỗộơớờởỡợ]/gi,
            u: /[úùủũụưứừửữự]/gi,
            y: /[ýỳỷỹỵ]/gi,
            d: /[đ]/gi
        };
        for (let key in map) {
            str = str.replace(map[key], key);
        }
        return str;
    }

    cleanSpecialChars(str) {
        return str.replace(/[`~!@#|$%^&*()+,./?><'":;]/gi, '');
    }

    camelToDash(str) {
        return str.replace(/[A-Z]/g, letter => this.separator + letter.toLowerCase());
    }

    removeDuplicateSeparators(str) {
        const sep = this.separator;
        const regex = new RegExp(`${sep}{2,}`, 'g');
        return str.replace(regex, sep);
    }

    trimSeparators(str) {
        const sep = this.separator;
        return str.replace(new RegExp(`(^${sep}|${sep}$)`, 'g'), '');
    }

    makeSlug(str) {
        if (!str) return '';
        str = this.normalizeArabicLetters(str);
        str = this.normalizeNumbers(str);
        str = this.normalizeAccents(str);
        str = this.camelToDash(str);
        str = this.cleanSpecialChars(str);
        str = str.replace(/\s+/g, this.separator);
        str = this.removeDuplicateSeparators(str);
        str = this.trimSeparators(str);
        return str.toLowerCase();
    }

    attachToInput(selector = 'input[name="slug"]') {
        const inputs = document.querySelectorAll(selector);
        inputs.forEach(input => {
            input.addEventListener('focusout', () => {
                input.value = this.makeSlug(input.value);
            });
        });
    }
}

export function slugify() {
    const inputEls = document.querySelectorAll('.slug-input');

    if (inputEls.length) {
        inputEls.forEach(el => {
            el.addEventListener('change', e => {

            })
        })
    }
}


export function initInlineSelectBox(selector = 'select[data-inline]') {

    console.log(caches)
    const selectEls = document.querySelectorAll(selector);

    if (!selectEls.length) return;

    if (!caches.has('toHasInitBefore')) import('tom-select/dist/css/tom-select.css')

    if (!(caches.has('isSelectExist') && !caches.caches)) caches.add('toHasInitBefore');

    selectEls.forEach(el => {
        singleTomSelect(el);
    })
}

function singleTomSelect(el) {
    const create = el.getAttribute('data-creation') ?? false;
    const maxItem = el.getAttribute('data-maxItem') ?? null;
    const createFilter = el.getAttribute('data-createFilter') ?? null;
    const hideSelected = el.getAttribute('data-hideSelected') ?? false;
    const duplicates = el.getAttribute('data-duplicates') ?? false;


    const settings = {
        plugins: ['no_backspace_delete', 'remove_button',],
        create: create,
        maxItem: maxItem,
        createFilter: createFilter,
        hideSelected: hideSelected,
        duplicates: duplicates,
        sortField: {
            field: "text",
            direction: "asc"
        }
    };
    new TomSelect(el, settings);
}


export function runObserver() {
    const observer = new MutationObserver((mutations) => {
        mutations.forEach(node => {
            if (!(node.target instanceof HTMLInputElement)) return;
            tomSelectMutation(node)
        });
    });


    observer.observe(document.body, {
        childList: true,
        subtree: true,
    })
}

function tomSelectMutation(node) {
    if (node.matches('select[data-inline]')) {
        singleTomSelect(node)
    }
}

