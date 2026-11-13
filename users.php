<?php require_once __DIR__ . '/controllers/UsersController.php'; require_perm('users'); $d = users_page(); require __DIR__ . '/views/users_view.php';
