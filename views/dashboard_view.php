<?php
/** @var array $d Datos del dashboard */
$page_title = t('dashboard'); 
require __DIR__ . '/layout_header.php'; 
$u = current_user(); 
$avatar = ($u['avatar'] ?? '') ?: 'assets/img/avatars/avatar-' . ($u['role'] ?? 'estudiante') . '.svg'; 
$name = ($u['full_name'] ?? '') ?: ($u['username'] ?? 'Usuario'); 
$role = $u['role'] ?? 'estudiante';
?>

<section class="card animate-in">
    <div style="display:flex;align-items:center;gap:16px">
        <img src="<?= e($avatar) ?>" alt="Avatar" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--primary)" onerror="this.src='assets/img/avatars/avatar-<?= e($u['role'] ?? 'estudiante') ?>.svg'">
        <div>
            <h2 class="metal-text" style="margin:0"><?= e(t('welcome')) ?>, <?= e($name) ?></h2>
            <p style="margin:4px 0 0;color:var(--muted)"><?= e(ucfirst($role)) ?> · <?= e($u['email'] ?? 'Sin correo') ?></p>
        </div>
    </div>
</section>

<?php if ($role === 'admin' || $role === 'secretaria'): ?>
    <!-- VISTA EXCLUSIVA PARA ADMINISTRACIÓN -->
    <section class="cards">
        <?php foreach ($d['cards'] as $label => $value): ?>
            <article class="card stat-card reveal">
                <h3><?= e($label) ?></h3>
                <strong class="count" data-count="<?= (int)$value ?>"><?= (int)$value ?></strong>
            </article>
        <?php endforeach; ?>
    </section>
    <section class="card animate-in" style="margin-top:16px;">
        <h3>📊 Resumen Rápido de Actividad</h3>
        <p>Bienvenido al panel de control. Desde aquí puede gestionar usuarios, configurar el sistema y revisar alertas tempranas.</p>
        <div style="margin-top:10px;">
            <a href="alerts.php" class="btn btn-accent">🚨 Ver Alertas Tempranas</a>
            <a href="users.php" class="btn btn-metal">👥 Gestionar Usuarios</a>
        </div>
    </section>

<?php elseif ($role === 'docente'): ?>
    <!-- VISTA EXCLUSIVA PARA DOCENTES -->
    <section class="card animate-in" style="margin-top:16px; border-left: 4px solid var(--primary);">
        <h3>👨‍🏫 Panel del Docente</h3>
        <p>Tienes acceso para registrar asistencia, ingresar calificaciones de tus materias y consultar tu horario.</p>
        <div style="margin-top:15px; display:flex; gap:10px; flex-wrap:wrap;">
            <a href="attendance.php" class="btn btn-metal">✅ Tomar Asistencia Hoy</a>
            <a href="grades.php" class="btn btn-accent">📚 Ingresar Calificaciones</a>
            <a href="schedules.php" class="btn">🕒 Mi Horario</a>
        </div>
    </section>
    <?php if (!empty($d['recent'])): ?>
        <section class="card animate-in" style="margin-top:16px;">
            <h3>Últimos Registros de Asistencia</h3>
            <div class="table-responsive"><table class="table">
                <?php foreach (array_slice($d['recent'], 0, 5) as $row): ?>
                    <tr><?php foreach ($row as $v): ?><td><?= e($v) ?></td><?php endforeach; ?></tr>
                <?php endforeach; ?>
            </table></div>
        </section>
    <?php endif; ?>

<?php elseif ($role === 'padre'): ?>
    <!-- VISTA EXCLUSIVA PARA PADRES DE FAMILIA -->
    <section class="card animate-in" style="margin-top:16px; border-left: 4px solid var(--primary);">
        <h3>👨‍👩‍👦 Panel de Familia</h3>
        <p>Aquí puede monitorear el progreso académico y la asistencia de sus hijos.</p>
    </section>
    <?php if (!empty($d['children'])): ?>
        <?php foreach ($d['children'] as $ch): ?>
            <section class="card animate-in" style="margin-top:16px;">
                <h3>👤 <?= e($ch['first_name'] . ' ' . $ch['last_name']) ?></h3>
                <p><strong>RUDE:</strong> <?= e($ch['rude']) ?> | <strong>Curso:</strong> <?= e($ch['curso'] ?? 'No asignado') ?></p>
                <?php if (!empty($ch['recent_attendance'])): ?>
                    <h4 style="margin-top:15px;">Última Asistencia</h4>
                    <div class="table-responsive"><table class="table"><tr><th>Fecha</th><th>Estado</th></tr>
                    <?php foreach (array_slice($ch['recent_attendance'], 0, 5) as $a): ?>
                        <tr><td><?= e($a['attendance_date']) ?></td><td><?= e($a['status']) ?></td></tr>
                    <?php endforeach; ?>
                    </table></div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>

<?php elseif ($role === 'estudiante'): ?>
    <!-- VISTA EXCLUSIVA PARA ESTUDIANTES -->
    <section class="card animate-in" style="margin-top:16px; border-left: 4px solid var(--primary);">
        <h3>🎓 Mi Panel Estudiantil</h3>
        <p>Revisa tus calificaciones, tu horario de clases y los comunicados importantes.</p>
        <div style="margin-top:15px;">
            <a href="grades.php" class="btn btn-metal">📚 Ver mis Calificaciones</a>
            <a href="schedules.php" class="btn btn-accent">🕒 Mi Horario</a>
        </div>
    </section>
    <?php if (!empty($d['grades'])): ?>
        <section class="card animate-in" style="margin-top:16px;">
            <h3>Mis Calificaciones Recientes</h3>
            <div class="table-responsive"><table class="table"><tr><th>Materia</th><th>Trimestre</th><th>Nota</th></tr>
            <?php foreach (array_slice($d['grades'], 0, 5) as $g): ?>
                <tr><td><?= e($g['subject']) ?></td><td><?= e($g['term_name']) ?></td><td><strong><?= e($g['score']) ?></strong></td></tr>
            <?php endforeach; ?>
            </table></div>
        </section>
    <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/layout_footer.php'; ?>