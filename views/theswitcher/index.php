<form class="default">
    <fieldset>
        <legend><?= _('Designs an-/ausschalten') ?></legend>

        <table class="default nohover">
            <tbody>
                <? foreach ($stylesheets as $stylesheet) : ?>
                <tr>
                    <td>
                        <input type="checkbox" onChange="$('html').toggleClass('mycss_<?= $stylesheet->getId() ?>'); $.post(STUDIP.URLHelper.getURL('plugins.php/mycss/theswitcher/toggle/<?= $stylesheet->getId() ?>'));"<?= $stylesheet['active'] ? ' checked' : ''?>>
                    </td>
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
                        <a href="<?= PluginEngine::getLink($plugin, [], 'styles/edit/'.$stylesheet->getId()) ?>" data-dialog="size=large">
                            <?= Icon::create('edit', Icon::ROLE_CLICKABLE)->asImg(20, ['class' => "text-bottom"]) ?>
                        </a>
                    </td>
                </tr>
                <? endforeach ?>
            </tbody>
            <? if (count($public_stylesheets)) : ?>
            <tbody>
                <tr>
                    <td colspan="4">
                        <?= _('Und vom öffentlichen Marktplatz ...') ?>
                    </td>
                </tr>
                <? foreach ($public_stylesheets as $stylesheet) : ?>
                <tr>
                    <td>
                        <a href="<?= URLHelper::getLink('dispatch.php/profile', ['username' => get_username($stylesheet['range_id'])]) ?>">
                            <?= Avatar::getAvatar($stylesheet['range_id'])->getImageTag(Avatar::SMALL) ?>
                        </a>
                    </td>
                    <td>
                        <?= htmlReady($stylesheet['title']) ?>
                    </td>
                    <td>
                        <? if ($stylesheet['description']) : ?>
                            <?= tooltipIcon($stylesheet['description']) ?>
                        <? endif ?>
                    </td>
                    <td class="actions">
                        <a href="<?= PluginEngine::getLink($plugin, [], 'market/use/'.$stylesheet->getId()) ?>"
                           data-confirm="<?= _("Dieses Design wirklich verwenden?") ?>"
                           title="<?= _("Design kopieren und selbst verwenden.") ?>">
                            <?= Icon::create('download', Icon::ROLE_CLICKABLE)->asImg(20, ['class' => "text-bottom"]) ?>
                        </a>
                    </td>
                </tr>
                <? endforeach ?>
            </tbody>
            <? endif ?>
        </table>

    </fieldset>
</form>
