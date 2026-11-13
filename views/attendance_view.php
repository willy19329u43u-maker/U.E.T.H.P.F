<?php
require_once __DIR__ . '/../controllers/AttendanceController.php';
$data = attendance_page();
/** @var array $data */
$page_title = t('attendance'); // Cambia 'students' por el nombre del módulo (ej: 'schedules', 'calendar')
require __DIR__ . '/layout_header.php';
?>
<section class="card animate-in">
  <h2 class="metal-text"><?= e(t('attendance')) ?></h2>
  <form method="get" class="form-grid form-inline" style="margin-bottom:16px">
    <label class="field"><span>Curso</span><select class="input" name="course" onchange="this.form.submit()"><?php foreach ($data['courses'] as $c): ?><option value="<?= $c['id'] ?>" <?= $data['selected_course'] == $c['id'] ? 'selected' : '' ?>><?= e($c['name'] . ' ' . $c['parallel']) ?></option><?php endforeach; ?></select></label>
    <label class="field"><span>Fecha</span><input class="input" type="date" name="date" value="<?= e($data['date']) ?>" onchange="this.form.submit()"></label>
  </form>
  <?php if (empty($data['students'])): ?><p>No hay estudiantes registrados en este curso o no tiene acceso.</p>
  <?php else: ?>
    <form method="post" action="attendance.php"><?= csrf_field() ?><input type="hidden" name="course_id" value="<?= $data['selected_course'] ?>"><input type="hidden" name="date" value="<?= e($data['date']) ?>">
    <div class="table-responsive"><table class="table">
      <thead><tr><th>Estudiante</th><th>RUDE/CI</th><th>Estado</th><th>Motivo</th></tr></thead>
      <tbody>
        <?php foreach ($data['students'] as $st): $cur = $data['existing'][$st['id']] ?? ['status' => 'P', 'reason' => '']; ?>
          <tr>
            <td><?= e($st['name']) ?></td>
            <td><?= e($st['rude'] ?? $st['ci'] ?? '-') ?></td>
            <td>
              <select name="status[<?= $st['id'] ?>]" class="input">
                <option value="P" <?= ($cur['status'] ?? 'P') === 'P' ? 'selected' : '' ?>>✅ Asiste</option>
                <option value="F" <?= ($cur['status'] ?? 'P') === 'F' ? 'selected' : '' ?>>❌ Falta</option>
                <option value="R" <?= ($cur['status'] ?? 'P') === 'R' ? 'selected' : '' ?>>⏰ Retraso</option>
                <option value="L" <?= ($cur['status'] ?? 'P') === 'L' ? 'selected' : '' ?>>📄 Licencia</option>
                <option value="RC" <?= ($cur['status'] ?? 'P') === 'RC' ? 'selected' : '' ?>>🚪 Retirado</option>
              </select>
            </td>
            <td><input type="text" name="reason[<?= $st['id'] ?>]" value="<?= e($cur['reason'] ?? '') ?>" class="input" placeholder="Motivo"></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table></div>
    <div class="form-actions" style="margin-top:10px;"><button class="btn btn-metal" type="submit">Guardar Asistencia</button></div>
    </form>
  <?php endif; ?>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
