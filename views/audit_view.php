<?php
require_once __DIR__ . '/../controllers/AuditController.php';
$data = audit_page();
/** @var array $data */
$page_title = t('audit'); require __DIR__ . '/layout_header.php'; ?>
<section class="card animate-in">
    <h2 class="metal-text"><?= e(t('audit')) ?></h2>
    <div class="table-responsive"><table class="table">
        <thead><tr><th>ID</th><th>Usuario</th><th>Acción</th><th>Entidad</th><th>IP</th><th>Fecha</th></tr></thead>
        <tbody><?php foreach ($data['logs'] as $log): ?><tr><td><?= $log['id'] ?></td><td><?= e($log['username'] ?? 'Sistema') ?></td><td><span class="badge"><?= e($log['action']) ?></span></td><td><?= e($log['entity']) ?> #<?= e($log['entity_id']) ?></td><td><?= e($log['ip']) ?></td><td><?= e($log['created_at']) ?></td></tr><?php endforeach; ?></tbody>
    </table></div>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
