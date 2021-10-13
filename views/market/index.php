<table class="default">
    <thead>
        <tr>
            <th></th>
            <th><?= _("Name des Designs") ?></th>
            <th><?= _("Beschreibung") ?></th>
            <th class="actions"><?= _("Aktion") ?></th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($stylesheets as $stylesheet) : ?>
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
                <? if ($stylesheet['range_id'] === User::findCurrent()->id) : ?>
                    <a href="<?= PluginEngine::getLink($plugin, [], 'styles/edit/'.$stylesheet->getId()) ?>" data-dialog="size=large">
                        <?= Icon::create('edit', Icon::ROLE_CLICKABLE)->asImg(20, ['class' => "text-bottom"]) ?>
                    </a>
                <? else : ?>
                    <a href="<?= PluginEngine::getLink($plugin, [], 'market/use/'.$stylesheet->getId()) ?>"
                       data-confirm="<?= _("Dieses Design wirklich verwenden?") ?>"
                       title="<?= _("Design kopieren und selbst verwenden.") ?>">
                        <?= Icon::create('download', Icon::ROLE_CLICKABLE)->asImg(20, ['class' => "text-bottom"]) ?>
                    </a>
                <? endif ?>
            </td>
        </tr>
    <? endforeach ?>
    </tbody>
</table>
