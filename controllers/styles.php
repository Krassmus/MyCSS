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
        if (!$this->plugin->editor_loaded) {
            PageLayout::addScript($this->plugin->getPluginURL() . "/assets/ace/ace.js");
            PageLayout::addScript($this->plugin->getPluginURL() . "/assets/editor.js");
            PageLayout::addStylesheet($this->plugin->getPluginURL(). '/assets/mycss-editor.css');
        }
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
        PageLayout::addScript($this->plugin->getPluginURL() . "/assets/ace/ace.js");
        PageLayout::addScript($this->plugin->getPluginURL() . "/assets/editor.js");
        PageLayout::addStylesheet($this->plugin->getPluginURL(). '/assets/mycss-editor.css');
        PageLayout::addHeadElement('script', [], "
        $(function () {
            let editor = ace.edit('mycss-editor');
            $('#mycss-textarea').hide();
            editor.getSession().setValue($('#mycss-textarea').val());
            editor.getSession().on('change', function(){
                $('#mycss-textarea').val(editor.getSession().getValue());
            });
            editor.setTheme('ace/theme/xcode');
            editor.session.setMode('ace/mode/scss');
        });
        ");
        PageLayout::setTitle(_('Design bearbeiten'));
        if (Request::isPost()) {
            if (Request::submitted('delete')) {
                $this->stylesheet->delete();
                PageLayout::postSuccess(_('Design wurde gelöscht.'));
                $this->redirect(Request::get('mycss_redirect_url', 'styles/index'));
                return;
            }
            $data = Request::getArray('data');
            if (!$GLOBALS['perm']->have_perm('root')) {
                $data['range_type'] = 'user';
            }
            $data['range_id'] = User::findCurrent()->id;
            $this->stylesheet->setData($data);
            $this->stylesheet->store();
            PageLayout::postSuccess(_('Design wurde gespeichert'));
            $cache       = StudipCacheFactory::getCache();
            $cache_index = sprintf('mycss_%s', $this->stylesheet->getId());
            $cache->expire($cache_index);
            $this->redirect(Request::get('mycss_redirect_url', 'styles/index'));
        }
    }
}
