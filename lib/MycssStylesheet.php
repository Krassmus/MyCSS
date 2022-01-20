<?php

class MycssStylesheet extends SimpleORMap
{
    protected static function configure($config = array())
    {
        $config['db_table'] = 'mycss_stylesheets';
        $config['belongs_to']['origin'] = [
            'class_name'  => self::class,
            'foreign_key' => 'origin_id'
        ];

        $config['registered_callbacks']['after_store'][] = function (MycssStylesheet $stylesheet) {
            $cache_index = "mycss_{$stylesheet->id}";
            StudipCacheFactory::getCache()->expire($cache_index);
        };

        parent::configure($config);
    }

    public static function findMyActiveOnes()
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

    public function isEditable()
    {
        return $GLOBALS['perm']->have_perm('root')
            || $this->isNew()
            || $this['range_id'] === User::findCurrent()->id;
    }

    public function compile()
    {
        $cache       = StudipCacheFactory::getCache();
        $cache_index = "mycss_{$this->id}";

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
                PageLayout::postError(_('MyCSS-Fehler: ') . $e->getMessage());
            }
        }

        return $css;
    }

}


