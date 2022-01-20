<form action="<?= PluginEngine::getLink($plugin, [], 'styles/edit/'.$stylesheet->getId()) ?>"
      method="post"
      class="default">

    <label>
        <?= _('Name des Designs') ?>
        <input type="text" name="data[title]" value="<?= htmlReady($stylesheet['title']) ?>" required>
    </label>

    <label>
        <?= _('Beschreibung') ?>
        <textarea name="data[description]"><?= htmlReady($stylesheet['description']) ?></textarea>
    </label>

    <? if ($GLOBALS['perm']->have_perm('root')) : ?>
        <label>
            <?= _('Gültigkeit (diese Option sehen nur Root-User)') ?>
            <select name="data[range_type]">
                <option value="user"<?= $stylesheet['range_type'] == 'user' ? ' selected' : '' ?>><?= _('Nur für mich') ?></option>
                <option value="global"<?= $stylesheet['range_type'] == 'global' ? ' selected' : '' ?>><?= _('Für ALLE Nutzer') ?></option>
            </select>
        </label>
    <? endif ?>

    <input type="hidden" name="data[active]" value="0">
    <label>
        <input type="checkbox" name="data[active]" value="1"<?= $stylesheet['active'] || $stylesheet->isNew() ? ' checked' : '' ?>>
        <?= _('Aktiviert') ?>
    </label>

    <input type="hidden" name="data[public]" value="0">
    <label>
        <input type="checkbox" name="data[public]" value="1"<?= $stylesheet['public'] ? ' checked' : '' ?>>
        <?= _('Öffentlich für alle') ?>
    </label>



    <label>
        <?= _('SCSS-Angaben') ?>
        <textarea id="mycss-textarea" name="data[css]"><?= htmlReady($stylesheet['css']) ?></textarea>
        <div id="mycss-editor"></div>
    </label>

    <input type="hidden" id="mycss_redirect_url" name="mycss_redirect_url" value="">
    <script>
        $(function() {
            $('#mycss_redirect_url').val(window.location.href);
        });
    </script>

    <div data-dialog-button>
        <?= \Studip\Button::create(_('Speichern')) ?>
        <? if (!$stylesheet->isNew()) : ?>
            <?= \Studip\Button::create(_('Löschen'), 'delete', ['data-confirm' => _('Wirklich löschen?')]) ?>
        <? endif ?>
    </div>
</form>
