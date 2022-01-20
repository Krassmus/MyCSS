<?php
/**
 * @property MyCSS $plugin
 */
class StylesController extends PluginController
{
    protected $_autobind = true;

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

        $this->plugin->loadEditor();

        $this->stylesheets = [];
        if ($GLOBALS['perm']->have_perm("root")) {
            $this->stylesheets = MycssStylesheet::findBySQL("`range_type` = 'global' ORDER BY `title` ASC");
        }
        foreach (MycssStylesheet::findBySQL("`range_type` = 'user' AND `range_id` = ? ORDER BY `title` ASC", [User::findCurrent()->id]) as $stylesheet) {
            $this->stylesheets[] = $stylesheet;
        }
    }

    public function edit_action(MycssStylesheet $stylesheet = null)
    {
        if (!$this->stylesheet->isEditable()) {
            throw new AccessDeniedException();
        }
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
            $this->redirect(Request::get('mycss_redirect_url', 'styles/index'));
        }
    }
}
