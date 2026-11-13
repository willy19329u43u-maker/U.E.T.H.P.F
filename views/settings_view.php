<?php
require_once __DIR__ . '/../controllers/SettingsController.php';
$data = settings_page();
/** @var array $data */
$page_title = t('settings'); 
require __DIR__ . '/layout_header.php'; ?>
<section class="card animate-in">
    <h2 class="metal-text"><?= e(t('settings')) ?></h2>
    <form method="post" class="form-grid"><?= csrf_field() ?>
        <label class="field"><span>Nombre de la Institución</span><input class="input" type="text" name="app_name" value="<?= e($data['app_name']) ?>"></label>
        <label class="field"><span>Texto de Portada (Login)</span><textarea class="input" name="hero_text" rows="3"><?= e($data['hero_text']) ?></textarea></label>
        <label class="field"><span>Envío de Correos (SMTP)</span><select class="input" name="mail_enabled"><option value="1" <?= $data['mail_enabled']=='1'?'selected':'' ?>>Activado</option><option value="0" <?= $data['mail_enabled']=='0'?'selected':'' ?>>Desactivado</option></select></label>
        <div class="form-actions"><button class="btn btn-metal" type="submit"><?= e(t('save')) ?></button></div>
    </form>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
