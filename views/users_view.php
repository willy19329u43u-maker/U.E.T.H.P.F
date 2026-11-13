<?php
require_once __DIR__ . '/../controllers/UsersController.php';
$data = users_page();
/**
 * @var array $data Datos del módulo de usuarios (users_page())
 * @var array $data['users'] Lista de usuarios
 */
$page_title = t('users'); 
require __DIR__ . '/layout_header.php'; 

$edit = null; 
if (isset($_GET['edit'])) { 
    $st = db()->prepare('SELECT * FROM users WHERE id = ?'); 
    $st->execute([(int)$_GET['edit']]); 
    $edit = $st->fetch(); 
}
?>

<section class="card animate-in">
    <h2 class="metal-text"><?= e(t('users')) ?></h2>
    
    <?php if (can('users')): ?>
    <form method="post" action="users.php" class="form-grid" style="margin-bottom:20px; padding:15px; background:var(--surface-2); border-radius:12px;">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $edit['id'] ?? 0 ?>">
        
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:10px;">
            <label class="field">
                <span>Usuario (RUDE/CI)</span>
                <input class="input" type="text" name="username" value="<?= e($edit['username'] ?? '') ?>" required>
            </label>
            <label class="field">
                <span>Nombre Completo</span>
                <input class="input" type="text" name="full_name" value="<?= e($edit['full_name'] ?? '') ?>">
            </label>
            <label class="field">
                <span>Email</span>
                <input class="input" type="email" name="email" value="<?= e($edit['email'] ?? '') ?>">
            </label>
            <label class="field">
                <span>Rol</span>
                <select class="input" name="role" required>
                    <?php foreach(['admin'=>'Administrador','secretaria'=>'Secretaría','docente'=>'Docente','estudiante'=>'Estudiante','padre'=>'Padre'] as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($edit['role'] ?? '') === $k ? 'selected' : '' ?>><?= e($v) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="field">
                <span>Contraseña <?= $edit ? '(dejar vacío para no cambiar)' : '*' ?></span>
                <input class="input" type="password" name="password" <?= !$edit ? 'required' : '' ?>>
            </label>
            <label class="field">
                <span>Activo</span>
                <select class="input" name="active">
                    <option value="1" <?= ($edit['active'] ?? 1) == 1 ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= ($edit['active'] ?? 1) == 0 ? 'selected' : '' ?>>No</option>
                </select>
            </label>
        </div>
                    <!-- SECCIÓN DINÁMICA PARA DOCENTES -->
            <div id="advisor-section" style="display:none; margin-top:15px; padding:15px; background:var(--surface-2); border-radius:8px; border-left: 4px solid var(--primary);">
                <h4 style="margin-top:0; color:var(--primary);">Configuración Académica (Docente)</h4>
                
                <label style="display:flex; align-items:center; gap:8px; margin-bottom:10px; cursor:pointer;">
                    <input type="checkbox" name="is_asesor" value="1" <?= ($edit['is_asesor'] ?? 0) ? 'checked' : '' ?> style="width:18px; height:18px;">
                    <span style="font-weight:600;">¿Es Asesor de Curso?</span>
                </label>
                
                <div id="course-select-wrapper" style="display: <?= ($edit['is_asesor'] ?? 0) ? 'block' : 'none'; ?>">
                    <label class="field">
                        <span>Curso a Asesorar</span>
                        <select class="input" name="advisor_course_id">
                            <option value="0">-- Sin asignar --</option>
                            <?php 
                            // Cargar cursos para el select
                            $courses_list = db()->query('SELECT id, name, parallel FROM courses ORDER BY level, parallel')->fetchAll();
                            // Obtener el curso asesorado actual si estamos editando
                            $current_advisor_course = 0;
                            if ($edit && ($edit['is_asesor'] ?? 0)) {
                                $st_adv = db()->prepare('SELECT course_id FROM course_advisors WHERE user_id = ? LIMIT 1');
                                $st_adv->execute([$edit['id']]);
                                $current_advisor_course = $st_adv->fetch()['course_id'] ?? 0;
                            }
                            foreach ($courses_list as $c): 
                            ?>
                                <option value="<?= $c['id'] ?>" <?= $current_advisor_course == $c['id'] ? 'selected' : '' ?>>
                                    <?= e($c['name'] . ' ' . $c['parallel']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
            </div>

        <div class="form-actions" style="margin-top:10px;">
            <button class="btn btn-metal" type="submit"><?= e(t('save')) ?></button>
            <?php if ($edit): ?>
                <a href="users.php" class="btn">Cancelar</a>
            <?php endif; ?>
        </div>
    </form>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['users'] as $us): ?>
                    <tr>
                        <td><?= $us['id'] ?></td>
                        <td><?= e($us['username']) ?></td>
                        <td><?= e($us['full_name'] ?? '-') ?></td>
                        <td><?= e($us['email'] ?? '-') ?></td>
                        <td><span class="badge badge-accent"><?= e($us['role']) ?></span></td>
                        <td><?= $us['active'] ? '✔' : '' ?></td>
                        <td class="actions">
                            <?php if (can('users')): ?>
                                <a class="btn small" href="users.php?edit=<?= $us['id'] ?>">✏️</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="role"]');
    const advisorSection = document.getElementById('advisor-section');
    const courseWrapper = document.getElementById('course-select-wrapper');
    const isAsesorCheck = document.querySelector('input[name="is_asesor"]');

    // Mostrar/Ocultar sección según el rol
    roleSelect.addEventListener('change', (e) => {
        if (e.target.value === 'docente') {
            advisorSection.style.display = 'block';
        } else {
            advisorSection.style.display = 'none';
            isAsesorCheck.checked = false; // Resetear si cambia de rol
            courseWrapper.style.display = 'none';
        }
    });

    // Mostrar/Ocultar selector de curso según el checkbox
    if (isAsesorCheck) {
        isAsesorCheck.addEventListener('change', (e) => {
            courseWrapper.style.display = e.target.checked ? 'block' : 'none';
        });
    }

    // Ejecutar al cargar por si estamos editando un docente
    if (roleSelect.value === 'docente') {
        advisorSection.style.display = 'block';
        if (isAsesorCheck && isAsesorCheck.checked) {
            courseWrapper.style.display = 'block';
        }
    }
});
</script>

<?php require __DIR__ . '/layout_footer.php'; ?>
