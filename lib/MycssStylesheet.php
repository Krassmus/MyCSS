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
            $scss = '';
            $mixinFile = $GLOBALS['STUDIP_BASE_PATH'] . '/resources/assets/stylesheets/mixins.scss';
            foreach (file($mixinFile) as $mixin) {
                if (!preg_match('/@import "(.*)";/', $mixin, $match)) {
                    continue;
                }
                $scss .= file_get_contents($GLOBALS['STUDIP_BASE_PATH'] . '/resources/assets/stylesheets/' . $match[1].".scss") . "\n";
            }
            $scss .= sprintf('$image-path: "%s";', Assets::url('images')) . "\n";
            $scss .= '$icon-path: "${image-path}/icons/16";' . "\n";

            $scss = '.mycss_'.$this->getId().' { '.$this['css'].' }';
            $compiler = new \ScssPhp\ScssPhp\Compiler();
            try {
                $css = $compiler->compile($scss);
                $cache->write($cache_index, $css);
            } catch(ScssPhp\ScssPhp\Exception\ParserException $e) {
                PageLayout::postError(_('MyCSS-Fehler: ').$e->getMessage());
            }


        }
        return $css;
    }

}


