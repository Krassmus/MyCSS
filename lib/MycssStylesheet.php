<?php

class MycssStylesheet extends SimpleORMap
{

    static public function findMyActiveOnes()
    {
        $stylesheets = [];
        foreach (static::findBySQL("`range_type` = 'global' AND `active` = '1'") as $style) {
            $stylesheets[] = $style;
        }
        foreach (static::findBySQL("`range_type` = 'user' AND `range_id` = ? ", [User::findCurrent()->id]) as $style) {
            $stylesheets[] = $style;
        }
        return $stylesheets;
    }

    protected static function configure($config = array())
    {
        $config['db_table'] = 'mycss_stylesheets';
        $config['belongs_to']['origin'] = [
            'class_name'  => 'MycssStylesheet',
            'foreign_key' => 'origin_id'
        ];
        parent::configure($config);
    }

    public function isEditable()
    {
        if ($GLOBALS['perm']->have_perm("root") || $this->isNew() || ($this['range_id'] === User::findCurrent()->id)) {
            return true;
        }
        return false;
    }

    public function compile()
    {
        $cache       = StudipCacheFactory::getCache();
        $cache_index = sprintf('mycss_%s', $this->getId());

        $css = $cache->read($cache_index);
        if ($css === false) {
            try {
                $css = Assets\SASSCompiler::getInstance()->compile(sprintf(
                        '.mycss_%s { %s }',
                        $this->getId(),
                        $this->css
                    ));
                $cache->write($cache_index, $css);
            } catch (Exception $e) {
                PageLayout::postError(_('MyCSS-Fehler: ').$e->getMessage());
            }
            $scss = '';

        }
        return $css;
    }

}


