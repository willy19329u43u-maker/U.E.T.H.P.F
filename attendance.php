<?php require_once __DIR__ . '/controllers/AttendanceController.php'; require_perm('attendance'); $d = attendance_page(); require __DIR__ . '/views/attendance_view.php';
