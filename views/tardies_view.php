<?php
require_once __DIR__ . '/../controllers/TardiesController.php';
$data = tardies_page();
$page_title = t('tardies'); 
require __DIR__ . '/layout_header.php';
$students = db()->query("SELECT id, CONCAT(first_name, ' ', last_name) AS name FROM students WHERE status=1 ORDER BY last_name")->fetchAll();
$courses = db()->query('SELECT id, name, parallel FROM courses ORDER BY name')->fetchAll();
?>
<section class="card animate-in">
    <h2 class="metal-text"><?= e(t('tardies')) ?></h2>
    <?php if (can('tardies')): ?>
    <form method="post" action="tardies.php" class="form-grid" style="margin-bottom:20px; padding:15px; background:var(--surface-2); border-radius:12px;">
        <?= csrf_field() ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:10px;">
            <label class="field"><span>Estudiante</span><select class="input" name="student_id" required><?php foreach ($students as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['name']) ?></option><?php endforeach; ?></select></label>
            <label class="field"><span>Fecha</span><input class="input" type="date" name="tardy_date" value="<?= date('Y-m-d') ?>" required></label>
            <label class="field"><span>Hora Llegada</span><input class="input" type="time" name="arrival_time" required></label>
            <label class="field"><span>Curso</span><select class="input" name="course_id"><?php foreach ($courses as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['name'] . ' ' . $c['parallel']) ?></option><?php endforeach; ?></select></label>
            <label class="field"><span>Estado</span><select class="input" name="status"><option value="justificado">Justificado</option><option value="injustificado">Injustificado</option></select></label>
            <label class="field"><span>Justificación</span><input class="input" type="text" name="justification"></label>
        </div>
        <div class="form-actions" style="margin-top:10px;"><button class="btn btn-metal" type="submit"><?= e(t('save')) ?></button></div>
    </form>
    <?php endif; ?>
    <div class="table-responsive"><table class="table">
        <thead><tr><th>Estudiante</th><th>Fecha</th><th>Hora</th><th>Curso</th><th>Estado</th><th>Justificación</th></tr></thead>
        <tbody><?php foreach ($data['tardies'] as $t): ?><tr><td><?= e($t['student_name']) ?></td><td><?= e($t['tardy_date']) ?></td><td><?= e($t['arrival_time']) ?></td><td><?= e($t['course_name'] ?? '-') ?></td><td><span class="badge"><?= e($t['status']) ?></span></td><td><?= e($t['justification'] ?? '') ?></td></tr><?php endforeach; ?></tbody>
    </table></div>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
