<?php require_once __DIR__ . '/core/bootstrap.php'; session_start(); echo "✅ Sesión: " . (session_id() ? "ACTIVA (" . session_id() . ")" : "INACTIVA");
