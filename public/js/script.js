'use strict';
document.body.addEventListener('htmx:afterSwap', event => {
    if(event.detail.requestConfig.verb !== 'get') setTimeout(() => document.getElementById(event.target.id).innerText = '', 4000);
});