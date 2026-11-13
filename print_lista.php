<?php require_once __DIR__ . '/controllers/PrintController.php'; require_perm('attendance'); $d = print_lista_page(); require __DIR__ . '/views/print_lista_view.php';
