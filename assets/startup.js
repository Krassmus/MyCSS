(function (STUDIP) {
    'use strict';

    const html = document.querySelector('html');
    STUDIP.MyCSS.stylesheets.forEach(styleClass => {
        html.classList.add(styleClass);
    });
}(STUDIP));
