<?php

require_once __DIR__."/lib/MycssStylesheet.php";

class MyCSS extends StudIPPlugin implements SystemPlugin
{
    public function __construct()
    {
        parent::__construct();

        if ($GLOBALS['perm']->have_perm(Config::get()->MYCSS_EDIT_PERM)) {
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
        }
    }
}
