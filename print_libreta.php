<?php require_once __DIR__ . '/controllers/PrintController.php'; require_perm('students'); $d = print_libreta_page(); require __DIR__ . '/views/print_libreta_view.php';
