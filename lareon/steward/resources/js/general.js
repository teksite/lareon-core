import TomSelect from "tom-select";

let caches = new Set();


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


export function initInlineSelectBox(selector = 'select[data-inline]') {

    const selectEls = document.querySelectorAll(selector);

    if (!selectEls.length) return;

    if (!caches.has('tomInitBefore')) {
        caches.add('tomInitBefore');
        import('tom-select/dist/css/tom-select.css');
    }

    selectEls.forEach(el => {
        initTomSelect(el);
    })
}

function initTomSelect(el) {

    if (el.tomselect) return;

    const {creation, maxItem, createFilter, hideSelected, duplicates} = el.dataset;

    new TomSelect(el, {
        plugins: [
            'remove_button',
            'no_backspace_delete'
        ],
        create: creation === 'true',
        maxItems: maxItem ? Number(maxItem) : null,
        createFilter,
        hideSelected: hideSelected !== 'false',
        duplicates: duplicates === 'true',
        sortField: {
            field: 'text',
            direction: 'asc'
        }
    });
}


function tomSelectMutation(node) {
    if (node.matches?.('select[data-inline]')) {
        initTomSelect(node);
        return;
    }
    const selectEls = node.querySelectorAll('select[data-inline]');
    selectEls.forEach(el => {
        if (el.tomselect) return;
        initTomSelect(el);
    })
}



export function formTabObserver() {
    document.querySelectorAll('.editor.tab-contents .tab-item').forEach((el, i) => {
        el.setAttribute('x-show', `activeTab === ${i}`)
        el.removeAttribute('style')
    })

    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (e) {

            if (form.checkValidity()) return;

            e.preventDefault()

            const invalidFields = Array.from(form.querySelectorAll(':invalid'))
                .filter(f => f.willValidate)

            if (invalidFields.length === 0) return

            const firstField = invalidFields[0]
            const tabItem = firstField.closest('.tab-item')
            const tabContainer = tabItem?.closest('.tab-contents')
            const wrapperEl = tabContainer?.closest('[x-data]')

            const switchTabAndFocus = () => {
                requestAnimationFrame(() => {
                    firstField.focus()
                    firstField.reportValidity()
                })
            }

            if (tabItem && tabContainer && wrapperEl) {
                const items = Array.from(tabContainer.children).filter(c => c.classList.contains('tab-item'))
                const index = items.indexOf(tabItem)
                const alpineData = window.Alpine.$data(wrapperEl)
                alpineData.activeTab = index

                window.Alpine.nextTick(switchTabAndFocus)
            } else {
                switchTabAndFocus()
            }
        })
    })
}

let observer;
export function runObserver() {

    if (observer) return;

    observer = new MutationObserver(mutations => {

        for (const mutation of mutations) {

            for (const node of mutation.addedNodes) {
                if (node.nodeType !== Node.ELEMENT_NODE) continue;
                tomSelectMutation(node);
            }
        }

    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

