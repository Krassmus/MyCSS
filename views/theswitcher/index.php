<form class="default">
    <fieldset>
        <legend><?= _('Designs an-/ausschalten') ?></legend>

        <table class="default nohover">
            <tbody>
                <? foreach ($stylesheets as $stylesheet) : ?>
                <tr>
                    <td>
                        <a href="<?= PluginEngine::getURL($plugin, [], 'styles/index') ?>">
                            <?= htmlReady($stylesheet['title']) ?>
                        </a>
                    </td>
                    <td>
                        <? if ($stylesheet['description']) : ?>
                        <?= tooltipIcon($stylesheet['description']) ?>
                        <? endif ?>
                    </td>
                    <td class="actions">
                        <input type="checkbox" onChange="$('html').toggleClass('mycss_<?= $stylesheet->getId() ?>'); $.post(STUDIP.URLHelper.getURL('plugins.php/mycss/theswitcher/toggle/<?= $stylesheet->getId() ?>'));"<?= $stylesheet['active'] ? ' checked' : ''?>>
                    </td>
                </tr>
                <? endforeach ?>
            </tbody>
        </table>

    </fieldset>
</form>
