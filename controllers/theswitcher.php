<?php

class TheswitcherController extends PluginController
{
    public function index_action()
    {
        $this->stylesheets = [];
        if ($GLOBALS['perm']->have_perm("root")) {
            $this->stylesheets = MycssStylesheet::findBySQL("`range_type` = 'global' ORDER BY `title` ASC");
        }
        foreach (MycssStylesheet::findBySQL("`range_type` = 'user' AND `range_id` = ? ORDER BY `title` ASC", [User::findCurrent()->id]) as $stylesheet) {
            $this->stylesheets[] = $stylesheet;
        }

        $this->public_stylesheets = MycssStylesheet::findBySQL("`public` = '1' AND `range_id` != ? ORDER BY `chdate` DESC LIMIT 20", [User::findCurrent()->id]);
    }

    public function toggle_action($stylesheet_id)
    {
        $this->stylesheet = MycssStylesheet::find($stylesheet_id);
        if (!$this->stylesheet->isEditable()) {
            throw new AccessDeniedException();
        }
        if (Request::isPost()) {
            $this->stylesheet['active'] = $this->stylesheet['active'] ? 0 : 1;
            $this->stylesheet->store();
        }
        $this->render_nothing();
    }
}
