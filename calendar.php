<?php require_once __DIR__ . '/controllers/CalendarController.php'; require_perm('calendar'); $d = calendar_page(); require __DIR__ . '/views/calendar_view.php';
