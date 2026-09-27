import Sortable from 'sortablejs';

/**
 * Initializes the nested sortable menus and sets up event listeners.
 */
function initializeMenuManager() {
    const nestedMenus = document.getElementById('nestedMenus');
    if (!nestedMenus) return;

    setupSortableMenus();
    setupAddMenuItem();
    setupDeleteMenuItem();
}

/**
 * Sets up Sortable.js for all nested-sortable elements.
 */
function setupSortableMenus() {
    const sortableElements = document.querySelectorAll('.nested-sortable');
    sortableElements.forEach(element => {
        new Sortable(element, {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.2,
            invertSwap: true,
            handle: '.handle',
            onEnd: handleSortEnd,
        });
    });
}

/**
 * Handles the end of a sort event, updating positions and parent IDs.
 * @param {Object} evt - The Sortable.js event object.
 */
function handleSortEnd(evt) {
    const movedItem = evt.item;
    const parentContainer = movedItem.parentNode;
    const parentId = parentContainer.getAttribute('data-parent_id') || '';

    // Update parent_id for the moved item
    const parentField = movedItem.querySelector('.parent_id');
    if (parentField) parentField.value = parentId;

    // Update positions for all items
    updateItemPositions();
}

/**
 * Updates the position field for all menu items.
 */
function updateItemPositions() {
    const menuItems = document.querySelectorAll('.menu_item');
    menuItems.forEach((item, index) => {
        const positionField = item.querySelector('.position-item');
        if (positionField) positionField.value = index;
    });
}

/**
 * Sets up event listener for adding new menu items.
 */
function setupAddMenuItem() {
    const addForm = document.getElementById('createForm');
    addForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const nestedMenus = document.getElementById('nestedMenus');
        const newId = `rand${Date.now()}`;
        const position = document.querySelectorAll('.menu_item').length;
        const titleEl = addForm.querySelector('#newTitle');
        const urlEl = addForm.querySelector('#newUrl');
        const title = titleEl.value ?? '';
        const url = urlEl.value ?? '';
        nestedMenus.insertAdjacentHTML('beforeend', createMenuItemHtml(newId, position , title, url));

        titleEl.value=''
        urlEl.value=''
    });
}

/**
 * Sets up event delegation for deleting menu items.
 */
function setupDeleteMenuItem() {
    document.addEventListener('click', (e) => {
        if (!e.target.classList.contains('delete-menu-item')) return;

        const itemId = e.target.getAttribute('data-for');
        const itemElement = document.getElementById(`menu_item-${itemId}`);
        if (!itemElement) return;

        deleteMenuItem(itemElement);
    });
}

/**
 * Deletes a menu item and moves its children to the parent.
 * @param {HTMLElement} itemElement - The menu item element to delete.
 */
function deleteMenuItem(itemElement) {
    const parentContainer = itemElement.parentNode;
    const parentId = parentContainer.getAttribute('data-parent_id') || '';
    const childrenContainer = itemElement.querySelector('.nested-sortable');

    // Move children to parent
    if (childrenContainer) {
        Array.from(childrenContainer.children).forEach(child => {
            const childParentField = child.querySelector('.parent_id');
            if (childParentField) childParentField.value = parentId;
            parentContainer.insertBefore(child, itemElement.nextSibling);
        });
    }

    // Remove the item
    itemElement.remove();

    // Update positions
    updateItemPositions();
}

/**
 * Creates HTML for a new menu item.
 * @param {string} id - The unique ID for the menu item.
 * @param {number} position - The position of the menu item.
 * @param {string} title - The title of the menu item.
 * @param {string} url - The url of the menu item.
 * @returns {string} - The HTML string for the menu item.
 */
function createMenuItemHtml(id, position , title , url) {
    return `
        <div x-data="{open:false}" class="item border border-slate-300 mb-3 rounded overflow-hidden menu_item" data-id="${id}" id="menu_item-${id}">
            <button type="button" class="w-full flex items-center gap-3 bg-white outline-none" :class="open ? 'rounded-t' : 'rounded'" @click="open=!open">
                <span class="handle self-stretch px-2 py-1 bg-gray-400 cursor-all-scroll">✢</span>
                <span class="text-sm">${title}</span>
                <svg x="0px" y="0px" width="9" height="9" viewBox="0 0 24 24" class="tkicon ease-in-out transition-all icon-accordion" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" :class="{'!-rotate-90':open}" data-icon="angle-left">
                    <path d="M17.8,0.5L6.2,12l11.5,11.5"></path>
                </svg>
            </button>
            <div x-show="open" x-transition class="bg-white p-3">
                <div class="visible_fields">
                    ${createInputField(id, 'title', 'title', title ?? '')}
                    ${createInputField(id, 'url', 'url', url, 'ltr')}
                    ${createInputField(id, 'subtitle', 'subtitle', '')}
                    ${createInputField(id, 'classes', 'classes', '', 'ltr')}
                    <div class="grid gap-3 md:grid-cols-2 mb-3">
                        ${createInputField(id, 'preicon', 'pre icon', '', 'ltr')}
                        ${createInputField(id, 'nexticon', 'next icon', '', 'ltr')}
                    </div>
                    ${createInputField(id, 'image', 'image', '', 'ltr')}
                    <button type="button" class="text-red-700 delete-menu-item my-3" id="delete-${id}" data-for="${id}">delete</button>
                </div>
                <div class="hidden hidden_fields">
                    <input type="hidden" name="items[${id}][parent_id]" value="" class="parent_id">
                    <input type="hidden" class="position-item" name="items[${id}][position]" value="${position}">
                </div>
            </div>
        </div>
    `;
}

/**
 * Creates HTML for an input field.
 * @param {string} id - The unique ID for the menu item.
 * @param {string} name - The name of the input field.
 * @param {string} label - The label for the input field.
 * @param {string} value - The default value for the input field.
 * @param {string} [dir] - The direction of the input field (e.g., 'ltr').
 * @returns {string} - The HTML string for the input field.
 */
function createInputField(id, name, label, value, dir) {
    return `
        <div class="mb-3">
            <label class="text-zinc-600 font-bold text-sm mb-1 block select-none" for="${name}-${id}">${label}</label>
            <input type="text" class="border border-zinc-300 px-3 py-1 rounded focus:outline-2 focus:outline-blue-600 bg-transparent w-full"
                   name="items[${id}][${name === 'preicon' ? 'pre_icon' : name === 'nexticon' ? 'next_icon' : name}]"
                   id="${name}-${id}" value="${value}" ${dir ? `dir="${dir}"` : ''}>
        </div>
    `;
}

// Initialize the menu manager when the DOM is loaded
document.addEventListener('DOMContentLoaded', initializeMenuManager);
