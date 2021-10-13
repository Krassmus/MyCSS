<table class="default">
    <thead>
        <tr>
            <th><?= _('Name') ?></th>
            <th><?= _('Aktiv') ?></th>
            <th><?= _('Öffentlich') ?></th>
            <th class="actions"></th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($stylesheets as $stylesheet) : ?>
        <tr>
            <td>
                <a href="<?= PluginEngine::getLink($plugin, [], 'styles/edit/'.$stylesheet->getId()) ?>" data-dialog>
                    <?= htmlReady($stylesheet['title']) ?>
                </a>
            </td>
            <td>
                <?= Icon::create('checkbox-'.($stylesheet['active'] ? '': 'un').'checked', Icon::ROLE_INFO)->asImg(20, ['class' => "text-bottom"])  ?>
            </td>
            <td>
                <?= Icon::create('checkbox-'.($stylesheet['public'] ? '': 'un').'checked', Icon::ROLE_INFO)->asImg(20, ['class' => "text-bottom"])  ?>
            </td>
            <td class="actions">
                <a href="<?= PluginEngine::getLink($plugin, [], 'styles/edit/'.$stylesheet->getId()) ?>" data-dialog>
                    <?= Icon::create('edit', Icon::ROLE_CLICKABLE)->asImg(20, ['class' => 'text-bottom']) ?>
                </a>
            </td>
        </tr>
        <? endforeach ?>
        <? if (!count($stylesheets)) : ?>
        <tr>
            <td colspan="4">
                <?= _("Erstellen Sie neue Designs, um Ihr Stud.IP für Sie komplett anzupassen.") ?>
            </td>
        </tr>
        <? endif ?>
    </tbody>
</table>

<?

$actions = new ActionsWidget();
$actions->addLink(
    _('Design hinzufügen'),
    PluginEngine::getURL($plugin, [], 'styles/edit'),
    Icon::create('add', Icon::ROLE_CLICKABLE),
    ['data-dialog' => 1]
);
if (MycssStylesheet::countBySql("`public` = '1' ") > 0) {
    $actions->addLink(
        _('Marktplatz besuchen'),
        PluginEngine::getURL($plugin, [], 'market/index'),
        Icon::create('billboard', Icon::ROLE_CLICKABLE)
    );
}
Sidebar::Get()->addWidget($actions);
