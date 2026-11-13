<?php require_once __DIR__ . '/controllers/AnnouncementController.php'; require_perm('announcements'); $d = announcement_page(); require __DIR__ . '/views/announcements_view.php';
