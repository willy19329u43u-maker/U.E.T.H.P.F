<?php
require_once __DIR__ . '/../controllers/StudentsController.php';
$data = students_page();
/** @var array $data */
$page_title = t('students'); 
require __DIR__ . '/layout_header.php'; 
$edit = null;
if (isset($_GET['edit'])) { 
    $st = db()->prepare('SELECT * FROM students WHERE id = ?'); 
    $st->execute([(int)$_GET['edit']]); 
    $edit = $st->fetch(); 
}
$paginator = $data['paginator'] ?? null;
$search = $data['search'] ?? '';
?>
<section class="card animate-in">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:10px;margin-bottom:15px;flex-wrap:wrap">
        <h2 class="metal-text" style="margin:0"><?= e(t('students')) ?></h2>
        <form method="get" action="students.php" style="display:flex;gap:5px;margin:0">
            <input type="text" name="q" placeholder="Buscar estudiante..." value="<?= e($search) ?>" style="padding:6px 10px;border:1px solid var(--line);border-radius:6px;">
            <button class="btn btn-metal small" type="submit">🔍</button>
        </form>
    </div>
    <?php if (can('students') && !restricted('students.edit')): ?>
    <form method="post" action="students.php" class="form-grid" style="margin-bottom:20px; padding:15px; background:var(--surface-2); border-radius:12px;">
        <?= csrf_field() ?><input type="hidden" name="id" value="<?= $edit['id'] ?? 0 ?>">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:10px;">
            <label class="field"><span>Nombres</span><input class="input" type="text" name="first_name" value="<?= e($edit['first_name'] ?? '') ?>" required></label>
            <label class="field"><span>Apellidos</span><input class="input" type="text" name="last_name" value="<?= e($edit['last_name'] ?? '') ?>" required></label>
            <label class="field"><span>RUDE</span><input class="input" type="text" name="rude" value="<?= e($edit['rude'] ?? '') ?>"></label>
            <label class="field"><span>CI</span><input class="input" type="text" name="ci" value="<?= e($edit['ci'] ?? '') ?>"></label>
            <label class="field"><span>Fecha Nacimiento</span><input class="input" type="date" name="birth_date" value="<?= e($edit['birth_date'] ?? '') ?>"></label>
            <label class="field"><span>Género</span><select class="input" name="gender"><option value="">--</option><option value="M" <?= ($edit['gender'] ?? '')==='M'?'selected':'' ?>>Masculino</option><option value="F" <?= ($edit['gender'] ?? '')==='F'?'selected':'' ?>>Femenino</option></select></label>
            <label class="field"><span>Dirección</span><input class="input" type="text" name="address" value="<?= e($edit['address'] ?? '') ?>"></label>
            <label class="field"><span>Año Inscripción</span><input class="input" type="number" name="enrollment_year" value="<?= e($edit['enrollment_year'] ?? date('Y')) ?>"></label>
            <label class="field"><span>Curso</span><select class="input" name="course_id"><option value="0">-- Sin asignar --</option><?php foreach ($data['courses'] as $c): ?><option value="<?= $c['id'] ?>" <?= ($edit['course_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>><?= e($c['name'] . ' ' . $c['parallel']) ?></option><?php endforeach; ?></select></label>
            <label class="field"><span>Usuario (Estudiante)</span><select class="input" name="user_id"><option value="0">-- Ninguno --</option><?php foreach ($data['users'] as $u): ?><option value="<?= $u['id'] ?>" <?= ($edit['user_id'] ?? 0) == $u['id'] ? 'selected' : '' ?>><?= e($u['username']) ?></option><?php endforeach; ?></select></label>
            <label class="field"><span>Tutor</span><input class="input" type="text" name="guardian_name" value="<?= e($edit['guardian_name'] ?? '') ?>"></label>
            <label class="field"><span>Teléfono Tutor</span><input class="input" type="text" name="guardian_phone" value="<?= e($edit['guardian_phone'] ?? '') ?>"></label>
            <label class="field"><span>Email Tutor</span><input class="input" type="email" name="guardian_email" value="<?= e($edit['guardian_email'] ?? '') ?>"></label>
            <label class="field"><span>Observaciones</span><textarea class="input" name="observations" rows="2"><?= e($edit['observations'] ?? '') ?></textarea></label>
        </div>
        <div class="form-actions" style="margin-top:10px;"><button class="btn btn-metal" type="submit"><?= e(t('save')) ?></button><?php if ($edit): ?><a href="students.php" class="btn">Cancelar</a><?php endif; ?></div>
    </form>
    <?php endif; ?>
    <div class="table-responsive"><table class="table">
        <thead><tr><th>Nombre</th><th>RUDE/CI</th><th>Curso</th><th>Tutor</th><th>Teléfono</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php foreach ($data['students'] as $s): ?>
            <tr>
                <td><?= e($s['first_name'] . ' ' . $s['last_name']) ?></td>
                <td><?= e($s['rude'] ?: $s['ci']) ?></td>
                <td><?= e($s['course_name'] ?? '-') ?></td>
                <td><?= e($s['guardian_name'] ?? '-') ?></td>
                <td><?= e($s['guardian_phone'] ?? '-') ?></td>
                <td class="actions">
                    <?php if (can('students') && !restricted('students.edit')): ?>
                        <a class="btn small" href="students.php?edit=<?= $s['id'] ?>">✏️</a>
                        <a class="btn small" target="_blank" href="print_libreta.php?student=<?= $s['id'] ?>">🖨</a>
                        <form method="post" action="students.php" style="display:inline;" onsubmit="return confirm('¿Eliminar?')"><?= csrf_field() ?><input type="hidden" name="delete_id" value="<?= $s['id'] ?>"><button class="btn btn-danger small" type="submit">🗑</button></form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php if ($paginator): ?>
    <nav class="pagination" style="margin-top:15px;display:flex;justify-content:center;gap:8px;flex-wrap:wrap">
        <?php if ($paginator->hasPrev()): ?>
            <?php $prevUrl = 'students.php?page=' . $paginator->getPrevPage() . '&per_page=' . $paginator->getPerPage() . ($search ? '&q=' . urlencode($search) : ''); ?>
            <a class='btn small' href='<?= $prevUrl ?>'>← Anterior</a>
        <?php endif; ?>
        <?php foreach ($paginator->getLinks() as $link): ?>
            <?php $linkUrl = 'students.php?page=' . $link['page'] . '&per_page=' . $paginator->getPerPage() . ($search ? '&q=' . urlencode($search) : ''); ?>
            <a class='btn small <?= $link['active'] ? 'btn-metal' : '' ?>' href='<?= $linkUrl ?>'><?= e($link['label']) ?></a>
        <?php endforeach; ?>
        <?php if ($paginator->hasNext()): ?>
            <?php $nextUrl = 'students.php?page=' . $paginator->getNextPage() . '&per_page=' . $paginator->getPerPage() . ($search ? '&q=' . urlencode($search) : ''); ?>
            <a class='btn small' href='<?= $nextUrl ?>'>Siguiente →</a>
        <?php endif; ?>
    </nav>
    <p style="margin-top:10px;color:var(--muted);font-size:.85rem;text-align:center">
        Mostrando <?= (($paginator->getCurrentPage() - 1) * $paginator->getPerPage() + 1) ?> a <?= min($paginator->getCurrentPage() * $paginator->getPerPage(), $paginator->totalItems) ?> de <?= $paginator->totalItems ?> estudiantes
    </p>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
