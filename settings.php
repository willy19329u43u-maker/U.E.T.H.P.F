<?php require_once __DIR__ . '/controllers/SettingsController.php'; require_perm('settings'); $d = settings_page(); require __DIR__ . '/views/settings_view.php';
