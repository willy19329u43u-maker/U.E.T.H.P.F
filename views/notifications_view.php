<?php
require_once __DIR__ . '/../controllers/NotificationsController.php';
$data = notifications_page();
/** @var array $data */
$page_title = t('notifications'); 
require __DIR__ . '/layout_header.php'; ?>
<section class="card animate-in">
    <div class="section-head"><h2 class="metal-text"><?= e(t('notifications')) ?></h2><form method="post" action="notifications.php" style="display:inline;"><?= csrf_field() ?><button class="btn btn-accent" type="submit">✔ Marcar todas como leídas</button></form></div>
    <?php if (empty($data['notifs'])): ?><p>No tienes notificaciones.</p><?php else: ?><div style="display:flex; flex-direction:column; gap:10px;"><?php foreach ($data['notifs'] as $n): ?><article class="notification-item <?= $n['read_at'] ? 'read' : 'unread' ?>"><h4><?= e($n['title']) ?></h4><p><?= e($n['message']) ?></p><small style="color:var(--muted)"><?= e($n['created_at']) ?></small></article><?php endforeach; ?></div><?php endif; ?>
</section>
<?php require __DIR__ . '/layout_footer.php'; ?>
