<?php require_once __DIR__ . '/controllers/StudentsController.php'; require_perm('students'); $data = students_page(); require __DIR__ . '/views/students_view.php';
