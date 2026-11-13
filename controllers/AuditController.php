<?php
require_once __DIR__ . '/../core/bootstrap.php';
function audit_page() { require_perm('audit'); $logs = db()->query("SELECT a.*, u.username FROM audit_logs a LEFT JOIN users u ON u.id = a.user_id ORDER BY a.id DESC LIMIT 500")->fetchAll(); return ['logs' => $logs]; }
