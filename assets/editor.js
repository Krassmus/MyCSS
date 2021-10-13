STUDIP.dialogReady(function () {

    var element = $('#mycss-editor'),
        mode;
    if (element.length === 0) {
        return;
    }

    mode = element.data().mode || 'less';
    editor = CodeMirror.fromTextArea(element[0], {
        mode: mode,
        theme: 'elegant',
        lineNumbers: true,
        styleActiveLine: true,
        matchBrackets: true,
        indentUnit: 4,
        lineWrapping: true
    });

    $('.CodeMirror').resizable({
        resize: function() {
            editor.setSize($(this).width(), $(this).height());
        }
    });
});
