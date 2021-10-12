<?php

class TheswitcherController extends PluginController
{
    public function index_action()
    {
        $this->stylesheets = MycssStylesheet::findBySQL("`range_id` = ? AND `range_type` = 'user' ORDER BY `title` ASC", [User::findCurrent()->id]);
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
