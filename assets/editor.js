STUDIP.dialogReady(function () {

    var element = $('#mycss-editor'),
        mode;
    if (element.length === 0) {
        return;
    }

    function loadAsset(asset) {
        return new Promise((resolve, reject) => {
            if (asset.match(/\.js$/)) {
                const node = document.createElement('script');
                node.src = asset;
                node.onload = resolve;
                node.onerror = reject;
                document.head.appendChild(node);
            } else if (asset.match(/\.css$/)) {
                const node = document.createElement('link');
                node.rel = 'stylesheet';
                node.type = 'text/css';
                node.href = asset;
                node.onload = resolve;
                node.onerror = reject;
                document.head.appendChild(node);
            }
        });
    }

    function loadAssets(assets) {
        let promise = Promise.resolve();
        assets.forEach(asset => {
            promise = promise.then(() => {
                return loadAsset(asset);
            });
        });
        return promise;
    }

    loadAssets(STUDIP.MyCSS.editor.assets).then(() => {
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

});
