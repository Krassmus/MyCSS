<table class="default">
    <thead>
        <tr>
            <th><?= _('Name') ?></th>
            <th><?= _('Aktiv') ?></th>
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
                <?= Icon::create('checkbox-'.($stylesheet['active'] ? '': 'un').'checked', Icon::ROLE_CLICKABLE)->asImg(20, ['class' => "text-bottom"])  ?>
            </td>
            <td class="actions">
                <a href="<?= PluginEngine::getLink($plugin, [], 'styles/edit/'.$stylesheet->getId()) ?>" data-dialog>
                    <?= Icon::create('edit', Icon::ROLE_CLICKABLE)->asImg(20, ['class' => 'text-bottom']) ?>
                </a>
            </td>
        </tr>
        <? endforeach ?>
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
Sidebar::Get()->addWidget($actions);
