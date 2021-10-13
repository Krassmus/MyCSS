<?php

require_once __DIR__."/lib/MycssStylesheet.php";

class MyCSS extends StudIPPlugin implements SystemPlugin
{

    public $editor_loaded = false;

    public function __construct()
    {
        parent::__construct();

        if ($GLOBALS['perm']->have_perm(Config::get()->MYCSS_EDIT_PERM) && Navigation::hasItem('/profile/settings')) {
            $nav = new Navigation(_('Angepasstes Design'), PluginEngine::getURL($this, [], 'styles/index'));
            Navigation::addItem('/profile/settings/mycss', $nav);
        }

        $stylesheets = MycssStylesheet::findMyActiveOnes();
        $stylesheet_ids = [];
        foreach ($stylesheets as $stylesheet) {
            if ($stylesheet['active']) {
                $stylesheet_ids[] = 'mycss_' . $stylesheet->getId();
            }
        }
        PageLayout::addHeadElement(
            'script',
            [],
            'window.STUDIP.MyCSS = {"stylesheets": '.json_encode($stylesheet_ids).'};'
        );
        PageLayout::addScript($this->getPluginURL()."/assets/startup.js");
        foreach ($stylesheets as $stylesheet) {
            PageLayout::addHeadElement(
                'style',
                [],
                $stylesheet->compile()
            );
        }

        if ($GLOBALS['perm']->have_perm(Config::get()->MYCSS_EDIT_PERM) && count($stylesheets)) {
            PageLayout::addScript($this->getPluginURL()."/assets/theswitcher.js");

            PageLayout::addStylesheet($this->getPluginURL(). '/assets/codemirror/codemirror.css');
            PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/codemirror.js');
            PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/active-line.js');
            PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/match-brackets.js');
            PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/css.js');
            PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/less.js');
            PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/xml.js');
            PageLayout::addScript($this->getPluginURL(). '/assets/codemirror/htmlmixed.js');
            foreach (glob($this->getPluginPath() . '/assets/codemirror/theme/*.css') as $theme) {
                $theme = str_replace($this->getPluginPath(), '', $theme);
                PageLayout::addStylesheet($this->getPluginURL() . $theme);
            }
            PageLayout::addScript($this->getPluginURL()."/assets/editor.js");
            $this->editor_loaded = true;
        }
    }
}
