<form action="<?= PluginEngine::getLink($plugin, [], 'styles/edit/'.$stylesheet->getId()) ?>"
      method="post"
      class="default">

    <label>
        <?= _('Name des Designs') ?>
        <input type="text" name="data[title]" value="<?= htmlReady($stylesheet['title']) ?>" required>
    </label>

    <input type="hidden" name="data[active]" value="0">
    <label>
        <input type="checkbox" name="data[active]" value="1"<?= $stylesheet['active'] || $stylesheet->isNew() ? ' checked' : '' ?>>
        <?= _('Aktiviert') ?>
    </label>

    <label>
        <?= _('SCSS-Angaben') ?>
        <textarea id="mycss-editor" name="data[css]"><?= htmlReady($stylesheet['css']) ?></textarea>
    </label>

    <div data-dialog-button>
        <?= \Studip\Button::create(_('Speichern')) ?>
        <? if (!$stylesheet->isNew()) : ?>
            <?= \Studip\Button::create(_('Löschen'), 'delete', ['data-confirm' => _('Wirklich löschen?')]) ?>
        <? endif ?>
    </div>
</form>
