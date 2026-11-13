<?php
require_once __DIR__ . '/../controllers/AnnouncementController.php';
$data = announcement_page();
/** @var array $data */
$page_title = t('announcements'); // Cambia 'students' por el nombre del módulo (ej: 'schedules', 'calendar')
require __DIR__ . '/layout_header.php'; ?>
<section class="card animate-in">
  <h2 class="metal-text"><?= e(t('announcements')) ?></h2>
  <?php if (can('announcements')): ?>
    <form method="post" action="announcements.php" class="form-grid" style="margin-bottom:24px;padding:16px;background:var(--surface-2);border-radius:12px">
        <?= csrf_field() ?>
        <label class="field"><span>Título</span><input class="input" type="text" name="title" required></label>
        <label class="field"><span>Contenido</span><textarea class="input" name="content" rows="3" required></textarea></label>
        <label class="field"><span>Destinatario</span><select class="input" name="target_type" id="targetType" onchange="toggleTargets()"><option value="global">Global (Todos)</option><option value="course">Por Curso</option><option value="student">Estudiante Específico</option><option value="teacher_area">Docentes de una Materia</option><option value="advisor">Asesores</option><option value="subject">Materia Específica</option></select></label>
        <label class="field" id="sel_course" style="display:none"><span>Seleccionar Curso</span><select class="input" name="course_id"><?php foreach ($data['courses'] as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['name'] . ' ' . $c['parallel']) ?></option><?php endforeach; ?></select></label>
        <label class="field" id="sel_student" style="display:none"><span>Seleccionar Estudiante</span><select class="input" name="student_id"><?php foreach ($data['students'] as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['name']) ?></option><?php endforeach; ?></select></label>
        <label class="field" id="sel_subject" style="display:none"><span>Seleccionar Materia</span><select class="input" name="subject_id"><?php foreach ($data['subjects'] as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['name']) ?></option><?php endforeach; ?></select></label>
        <div class="form-actions"><button class="btn btn-metal" type="submit"><?= e(t('create')) ?></button></div>
    </form>
    <script>function toggleTargets(){const t=document.getElementById('targetType').value;document.getElementById('sel_course').style.display=(t==='course')?'block':'none';document.getElementById('sel_student').style.display=(t==='student')?'block':'none';document.getElementById('sel_subject').style.display=(t==='teacher_area'||t==='subject')?'block':'none';}</script>
  <?php endif; ?>
  <h3>Avisos Publicados</h3>
  <?php 
  $u = current_user(); $where = "1=1";
  if (($u['role'] ?? '') === 'padre') { $st_ids = array_column((new StudentModel())->children_of($u['id']), 'id'); $course_ids = array_column((new StudentModel())->children_of($u['id']), 'course_id'); if (!empty($st_ids)) { $in_st = implode(',', $st_ids); $in_c = implode(',', $course_ids); $where = "(target_type = 'global' OR (target_type = 'course' AND course_id IN ($in_c)) OR (target_type = 'student' AND student_id IN ($in_st)))"; } else { $where = "target_type = 'global'"; } }
  elseif (($u['role'] ?? '') === 'estudiante') { $st = (new StudentModel())->by_user($u['id']); if ($st) { $where = "(target_type = 'global' OR (target_type = 'course' AND course_id = {$st['course_id']}) OR (target_type = 'student' AND student_id = {$st['id']}))"; } }
  elseif (($u['role'] ?? '') === 'docente') { $where = "(target_type = 'global' OR target_type = 'advisor' OR (target_type = 'teacher_area' AND subject_id IN (SELECT subject_id FROM teacher_subjects WHERE user_id = {$u['id']})))"; }
  $ann = db()->query("SELECT a.*, u.full_name AS author FROM announcements a LEFT JOIN users u ON u.id = a.created_by WHERE $where ORDER BY a.created_at DESC LIMIT 50")->fetchAll();
  ?>
  <div class="cards"><?php foreach ($ann as $a): ?><article class="card reveal" style="position:relative"><?php if (can('announcements')): ?><form method="post" action="announcements.php" style="position:absolute;top:10px;right:10px" onsubmit="return confirm('¿Eliminar?')"><?= csrf_field() ?><input type="hidden" name="delete_id" value="<?= $a['id'] ?>"><button class="btn btn-danger small" type="submit">🗑</button></form><?php endif; ?><h3><?= e($a['title']) ?></h3><p><?= nl2br(e($a['content'])) ?></p><div style="margin-top:12px;font-size:.85rem;color:var(--muted)"><span class="badge"><?= e($a['target_type']) ?></span><span>Por: <?= e($a['author'] ?? 'Admin') ?></span> · <?= e($a['created_at']) ?></div></article><?php endforeach; ?><?php if (empty($ann)): ?><p>No hay avisos disponibles.</p><?php endif; ?></div>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
