<?php
require_once __DIR__ . '/../controllers/GradeController.php';
$data = grades_page();
/** @var array $data */
$page_title = t('grades'); 
require __DIR__ . '/layout_header.php'; 

// Incluir el generador de observaciones si existe
if (file_exists(__DIR__ . '/../controllers/ObservationGenerator.php')) {
    require_once __DIR__ . '/../controllers/ObservationGenerator.php';
}
?>
<section class="card animate-in">
    <h2 class="metal-text"><?= e(t('grades')) ?></h2>
    <?php if (!empty($data['courses']) && !$data['is_teacher_only']): ?><form method="get" class="form-grid form-inline" style="margin-bottom:16px"><label class="field"><span>Curso</span><select class="input" name="course" onchange="this.form.submit()"><?php foreach ($data['courses'] as $c): ?><option value="<?= $c['id'] ?>" <?= $data['selected_course'] == $c['id'] ? 'selected' : '' ?>><?= e($c['name'] . ' ' . $c['parallel']) ?></option><?php endforeach; ?></select></label></form><?php elseif ($data['is_teacher_only']): ?><p style="margin-bottom:16px;color:var(--muted)">Mostrando sus calificaciones asignadas.</p><?php endif; ?>
    <?php if (empty($data['students'])): ?><p>No hay estudiantes registrados.</p>
    <?php else: ?>
        <div class="table-responsive"><table class="table">
          <thead><tr><th>Estudiante</th><th>Materia</th><th>Trimestre</th><th>Nota</th><th>Observación</th></tr></thead>
          <tbody><?php foreach ($data['grades'] as $g): ?>
            <tr><td><?= e($g['student_name'] ?? 'Estudiante') ?></td><td><?= e($g['subject'] ?? '-') ?></td><td><?= e($g['term_name'] ?? '-') ?></td><td><?= e($g['score'] ?? '-') ?></td><td><?= e($g['observation'] ?? '') ?></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table></div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
