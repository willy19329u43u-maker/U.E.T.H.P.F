<?php require_once __DIR__ . '/controllers/DashboardController.php'; require_login(); $d = dashboard_data(); require __DIR__ . '/views/dashboard_view.php';
