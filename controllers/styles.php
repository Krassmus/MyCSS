<?php

class StylesController extends PluginController
{

    public function before_filter(&$action, &$args)
    {
        if (!$GLOBALS['perm']->have_perm(Config::get()->MYCSS_EDIT_PERM)) {
            throw new AccessDeniedException();
        }
        parent::before_filter($action, $args);
    }

    public function index_action()
    {
        Navigation::activateItem('/profile/settings/mycss');
        PageLayout::addStylesheet($this->plugin->getPluginURL(). '/assets/codemirror/codemirror.css');
        PageLayout::addScript($this->plugin->getPluginURL(). '/assets/codemirror/codemirror.js');
        PageLayout::addScript($this->plugin->getPluginURL(). '/assets/codemirror/active-line.js');
        PageLayout::addScript($this->plugin->getPluginURL(). '/assets/codemirror/match-brackets.js');
        PageLayout::addScript($this->plugin->getPluginURL(). '/assets/codemirror/css.js');
        PageLayout::addScript($this->plugin->getPluginURL(). '/assets/codemirror/less.js');
        PageLayout::addScript($this->plugin->getPluginURL(). '/assets/codemirror/xml.js');
        PageLayout::addScript($this->plugin->getPluginURL(). '/assets/codemirror/htmlmixed.js');

        foreach (glob($this->plugin->getPluginPath() . '/assets/codemirror/theme/*.css') as $theme) {
            $theme = str_replace($this->plugin->getPluginPath(), '', $theme);
            PageLayout::addStylesheet($this->plugin->getPluginURL() . $theme);
        }

        PageLayout::addScript($this->plugin->getPluginURL()."/assets/editor.js");
        $this->stylesheets = [];
        if ($GLOBALS['perm']->have_perm("root")) {
            $this->stylesheets = MycssStylesheet::findBySQL("`range_type` = 'global' ORDER BY `title` ASC");
        }
        foreach (MycssStylesheet::findBySQL("`range_type` = 'user' AND `range_id` = ? ORDER BY `title` ASC", [User::findCurrent()->id]) as $stylesheet) {
            $this->stylesheets[] = $stylesheet;
        }
    }

    public function edit_action($stylesheet_id = null)
    {
        $this->stylesheet = new MycssStylesheet($stylesheet_id);
        if (!$this->stylesheet->isEditable()) {
            throw new AccessDeniedException();
        }
        PageLayout::setTitle(_('Design bearbeiten'));
        if (Request::isPost()) {
            if (Request::submitted('delete')) {
                $this->stylesheet->delete();
                PageLayout::postSuccess(_('Design wurde gelöscht.'));
                $this->redirect('styles/index');
                return;
            }
            $data = Request::getArray('data');
            $data['range_type'] = 'user';
            $data['range_id'] = User::findCurrent()->id;
            $this->stylesheet->setData($data);
            $this->stylesheet->store();
            PageLayout::postSuccess(_('Design wurde gespeichert'));
            $cache       = StudipCacheFactory::getCache();
            $cache_index = sprintf('mycss_%s', $this->stylesheet->getId());
            $cache->expire($cache_index);
            $this->redirect('styles/index');
        }
    }
}
