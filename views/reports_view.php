<?php
require_once __DIR__ . '/../controllers/ReportsController.php';
$data = reports_page();
/** @var array $data */
$page_title = t('reports'); require __DIR__ . '/layout_header.php'; ?>
<section class="card animate-in">
    <h2 class="metal-text"><?= e(t('reports')) ?></h2>
    <form method="get" action="reports.php" class="form-grid form-inline" style="margin-bottom:20px;">
        <label class="field"><span>Tipo</span><select class="input" name="type"><option value="attendance" <?= $data['type']==='attendance'?'selected':'' ?>>Asistencia</option><option value="tardies" <?= $data['type']==='tardies'?'selected':'' ?>>Atrasos</option><option value="grades" <?= $data['type']==='grades'?'selected':'' ?>>Calificaciones</option></select></label>
        <label class="field"><span>Desde</span><input class="input" type="date" name="from" value="<?= e($data['from']) ?>"></label>
        <label class="field"><span>Hasta</span><input class="input" type="date" name="to" value="<?= e($data['to']) ?>"></label>
        <div class="form-actions"><button class="btn btn-metal" type="submit">Generar</button><a class="btn btn-accent" href="export_csv.php?type=<?= e($data['type']) ?>&from=<?= e($data['from']) ?>&to=<?= e($data['to']) ?>">⬇ CSV</a></div>
    </form>
    <div class="table-responsive"><table class="table"><?php if (empty($data['rows'])): ?><tr><td>Sin datos para los filtros seleccionados.</td></tr><?php else: ?><tr><?php foreach (array_keys($data['rows'][0]) as $h): ?><th><?= e($h) ?></th><?php endforeach; ?></tr><?php foreach ($data['rows'] as $row): ?><tr><?php foreach ($row as $v): ?><td><?= e($v) ?></td><?php endforeach; ?></tr><?php endforeach; ?><?php endif; ?></table></div>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
