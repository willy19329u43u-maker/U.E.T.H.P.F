<?php
require_once __DIR__ . '/../controllers/AlertController.php';
$data = alerts_page();
/** @var array $data */
$page_title = 'Alertas Tempranas';
require __DIR__ . '/layout_header.php';
?>

<section class="card animate-in">
    <h2 class="metal-text">🚨 Sistema de Alertas Tempranas</h2>
    
    <div class="cards" style="margin-bottom:20px;">
        <article class="card stat-card">
            <h3>Total Alertas</h3>
            <strong class="count"><?= $data['total'] ?></strong>
        </article>
        <article class="card stat-card" style="--primary:#d62828;">
            <h3>Alta Severidad</h3>
            <strong class="count"><?= count($data['high']) ?></strong>
        </article>
        <article class="card stat-card" style="--primary:#ffb703;">
            <h3>Severidad Media</h3>
            <strong class="count"><?= count($data['medium']) ?></strong>
        </article>
    </div>

    <?php if (!empty($data['high'])): ?>
    <h3 style="color:#d62828;">⚠️ Alertas Críticas</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>Estudiante</th><th>Tipo</th><th>Mensaje</th><th>Curso</th></tr>
            </thead>
            <tbody>
                <?php foreach ($data['high'] as $alert): ?>
                <tr style="background:rgba(214,40,40,0.1);">
                    <td><strong><?= e($alert['student_name']) ?></strong></td>
                    <td><span class="badge" style="background:#d62828;color:white;"><?= e($alert['alert_type']) ?></span></td>
                    <td><?= e($alert['message']) ?></td>
                    <td><?= e($alert['course_id'] ?? 'N/A') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if (!empty($data['medium'])): ?>
    <h3 style="color:#ffb703; margin-top:20px;">⚡ Alertas Moderadas</h3>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr><th>Estudiante</th><th>Tipo</th><th>Mensaje</th><th>Curso</th></tr>
            </thead>
            <tbody>
                <?php foreach ($data['medium'] as $alert): ?>
                <tr>
                    <td><?= e($alert['student_name']) ?></td>
                    <td><span class="badge"><?= e($alert['alert_type']) ?></span></td>
                    <td><?= e($alert['message']) ?></td>
                    <td><?= e($alert['course_id'] ?? 'N/A') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if (empty($data['all'])): ?>
    <p style="text-align:center;padding:40px;color:var(--muted);">✅ No hay alertas activas. ¡Todos los estudiantes están en buen camino!</p>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/layout_footer.php'; ?>
