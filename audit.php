<?php require_once __DIR__ . '/controllers/AuditController.php'; require_perm('audit'); $d = audit_page(); require __DIR__ . '/views/audit_view.php';
