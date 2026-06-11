'use strict';
document.body.addEventListener('htmx:afterSwap', event => {
    setTimeout(() => document.getElementById(event.target.id).innerText = '', 4000);
});