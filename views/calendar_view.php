<?php
require_once __DIR__ . '/../controllers/CalendarController.php';
$data = calendar_page();
/** @var array $data */
$page_title = t('calendar'); // Cambia 'students' por el nombre del módulo (ej: 'schedules', 'calendar')
require __DIR__ . '/layout_header.php'; ?>
<!-- Aquí empieza el HTML de tu vista (<section class="card animate-in">...) -->
<section class="card animate-in">
    <h2 class="metal-text"><?= e(t('calendar')) ?></h2>
    <?php if (can('calendar')): ?>
    <form method="post" action="calendar.php" class="form-grid" style="margin-bottom:20px; padding:15px; background:var(--surface-2); border-radius:12px;">
        <?= csrf_field() ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:10px;">
            <label class="field"><span>Título</span><input class="input" type="text" name="title" required></label>
            <label class="field"><span>Fecha</span><input class="input" type="date" name="event_date" required></label>
            <label class="field"><span>Tipo</span><select class="input" name="event_type"><option value="academico">Académico</option><option value="reunion">Reunión</option><option value="civico">Cívico</option><option value="examen">Examen</option><option value="otro">Otro</option></select></label>
            <label class="field"><span>Curso (Opcional)</span><select class="input" name="target_course_id"><option value="0">-- Global --</option><?php foreach ($data['courses'] as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['name'] . ' ' . $c['parallel']) ?></option><?php endforeach; ?></select></label>
            <label class="field"><span>Responsable</span><input class="input" type="text" name="responsible"></label>
            <label class="field" style="grid-column:span 2;"><span>Descripción</span><textarea class="input" name="description" rows="2"></textarea></label>
        </div>
        <div class="form-actions" style="margin-top:10px;"><button class="btn btn-metal" type="submit"><?= e(t('save')) ?></button></div>
    </form>
    <?php endif; ?>
    <div class="cards">
    <?php foreach ($data['events'] as $ev): ?>
        <article class="card reveal" style="position:relative">
            <?php if (can('calendar')): ?><form method="post" action="calendar.php" style="position:absolute;top:10px;right:10px" onsubmit="return confirm('¿Eliminar?')"><?= csrf_field() ?><input type="hidden" name="delete_id" value="<?= $ev['id'] ?>"><button class="btn btn-danger small" type="submit">🗑</button></form><?php endif; ?>
            <h3><?= e($ev['title']) ?></h3>
            <p><strong>📅 <?= e($ev['event_date']) ?></strong> <span class="badge"><?= e($ev['event_type']) ?></span></p>
            <p><?= nl2br(e($ev['description'])) ?></p>
            <small style="color:var(--muted)"><?= e($ev['course_name'] ?? 'Global') ?> · <?= e($ev['responsible']) ?></small>
        </article>
    <?php endforeach; ?>
    </div>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
