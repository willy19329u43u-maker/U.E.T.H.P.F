<?php
require_once __DIR__ . '/../controllers/ScheduleController.php';
$data = schedule_page();
/** @var array $data */
$page_title = t('schedules'); // Cambia 'students' por el nombre del módulo (ej: 'schedules', 'calendar')
require __DIR__ . '/layout_header.php'; ?>
<section class="card animate-in">
  <h2 class="metal-text"><?= e(t('schedules')) ?></h2>
  <?php if (!empty($data['courses']) && !$data['is_teacher_only']): ?><form method="get" class="form-grid form-inline" style="margin-bottom:16px"><label class="field"><span>Curso</span><select class="input" name="course" onchange="this.form.submit()"><?php foreach ($data['courses'] as $c): ?><option value="<?= $c['id'] ?>" <?= $data['selected_course'] == $c['id'] ? 'selected' : '' ?>><?= e($c['name'] . ' ' . $c['parallel']) ?></option><?php endforeach; ?></select></label></form><?php elseif ($data['is_teacher_only']): ?><p style="margin-bottom:16px;color:var(--muted)">Mostrando su horario de clases asignado.</p><?php endif; ?>
  <?php if (empty($data['schedules'])): ?><p>No hay horarios registrados.</p>
  <?php else: ?>
    <div class="table-responsive"><table class="table">
      <thead><tr><th>Día</th><th>Inicio</th><th>Fin</th><th>Materia</th><?php if ($data['is_teacher_only']): ?><th>Curso</th><?php endif; ?><th>Docente</th><th>Aula</th></tr></thead>
      <tbody><?php foreach ($data['schedules'] as $sc): ?><tr><td><?= e($sc['day_of_week']) ?></td><td><?= e($sc['start_time']) ?></td><td><?= e($sc['end_time']) ?></td><td><strong><?= e($sc['subject']) ?></strong></td><?php if ($data['is_teacher_only']): ?><td><?= e($sc['course'] . ' ' . $sc['parallel']) ?></td><?php endif; ?><td><?= e($sc['teacher'] ?? 'Por asignar') ?></td><td><?= e($sc['classroom'] ?? '-') ?></td></tr><?php endforeach; ?>
      </tbody>
    </table></div>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
