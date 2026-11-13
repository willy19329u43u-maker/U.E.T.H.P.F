<?php require_once __DIR__ . '/controllers/NotificationsController.php'; require_perm('notifications'); $d = notifications_page(); require __DIR__ . '/views/notifications_view.php';
