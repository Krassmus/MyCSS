$(function () {
    for (let i in window.STUDIP.MyCSS.stylesheets) {
        $('html').addClass(window.STUDIP.MyCSS.stylesheets[i]);
    }
});
