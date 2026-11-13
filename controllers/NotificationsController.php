<?php
require_once __DIR__ . '/../core/bootstrap.php';
function notifications_page() { require_login(); $u = current_user(); $notifs = db()->prepare('SELECT * FROM notifications WHERE user_id = ? ORDER BY id DESC LIMIT 100'); $notifs->execute([$u['id']]); return ['notifs' => $notifs->fetchAll()]; }
function notifications_mark_read() { require_login(); verify_csrf(); $u = current_user(); db()->prepare('UPDATE notifications SET read_at = NOW() WHERE user_id = ? AND read_at IS NULL')->execute([$u['id']]); flash('success', 'Notificaciones marcadas como leídas.'); redirect('notifications.php'); }
