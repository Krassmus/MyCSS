<?php

require_once __DIR__."/lib/MycssStylesheet.php";

class MyCSS extends StudIPPlugin implements SystemPlugin
{

    public $editor_loaded = false;
    private $mycss = [
        'stylesheets' => [],
        'editor'      => [
            'assets' => [],
        ],
    ];

    public function __construct()
    {
        parent::__construct();

        if ($GLOBALS['perm']->have_perm(Config::get()->MYCSS_EDIT_PERM) && Navigation::hasItem('/profile/settings')) {
            $nav = new Navigation(_('Angepasstes Design'), PluginEngine::getURL($this, [], 'styles/index'));
            Navigation::addItem('/profile/settings/mycss', $nav);
        }

        $stylesheets = MycssStylesheet::findMyActiveOnes();
        foreach ($stylesheets as $stylesheet) {
            if ($stylesheet['active']) {
                $this->mycss['stylesheets'][] = 'mycss_' . $stylesheet->getId();
            }
        }
        foreach ($stylesheets as $stylesheet) {
            PageLayout::addHeadElement(
                'style',
                [],
                $stylesheet->compile()
            );
        }

        if ($GLOBALS['perm']->have_perm(Config::get()->MYCSS_EDIT_PERM) && count($stylesheets)) {
            NotificationCenter::on('PageWillRender', function () {
                PageLayout::addHeadElement(
                    'script',
                    [],
                    'window.STUDIP.MyCSS = '.json_encode($this->mycss).';'
                );
                $this->addScript('assets/startup.js');
            });
            $this->addScript('assets/theswitcher.js');

            $this->loadEditor();
        }
    }

    public function loadEditor()
    {
        if ($this->editor_loaded) {
            return;
        }
        $this->mycss['editor']['assets'][] = $this->getPluginURL(). '/assets/ace/ace.js';
        $this->mycss['editor']['assets'][] = $this->getPluginURL(). '/assets/mycss-editor.css';
        $this->addScript('assets/editor.js');
        $this->editor_loaded = true;
    }
}
