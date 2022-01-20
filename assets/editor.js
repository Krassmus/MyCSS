STUDIP.dialogReady(function () {

    let element = $('#mycss-editor'),
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
        let editor = ace.edit("mycss-editor");
        $('#mycss-textarea').hide();
        editor.getSession().setValue($('#mycss-textarea').val());
        editor.getSession().on('change', function(){
            $('#mycss-textarea').val(editor.getSession().getValue());
        });
        editor.setTheme("ace/theme/xcode");
        editor.session.setMode("ace/mode/scss");
    });
});
