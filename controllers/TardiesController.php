<?php
require_once __DIR__ . '/../core/bootstrap.php';
function tardies_page() {
    require_perm('tardies');
    $tardies = db()->query("SELECT t.*, CONCAT(s.first_name, ' ', s.last_name) AS student_name, c.name AS course_name FROM tardies t INNER JOIN students s ON s.id = t.student_id LEFT JOIN courses c ON c.id = t.course_id ORDER BY t.tardy_date DESC, t.arrival_time DESC LIMIT 100")->fetchAll();
    return ['tardies' => $tardies];
}
function tardies_save() {
    require_perm('tardies'); verify_csrf(); $u = current_user();
    $data = ['student_id' => (int)($_POST['student_id'] ?? 0), 'tardy_date' => $_POST['tardy_date'] ?? date('Y-m-d'), 'arrival_time' => $_POST['arrival_time'] ?? null, 'course_id' => (int)($_POST['course_id'] ?? 0) ?: null, 'teacher_user_id' => $u['id'], 'justification' => trim($_POST['justification'] ?? ''), 'status' => trim($_POST['status'] ?? 'injustificado')];
    try { $cols = array_keys($data); $sql = 'INSERT INTO tardies (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')'; db()->prepare($sql)->execute($data); flash('success', 'Atraso registrado.'); audit('create_tardy', 'tardies', (int)db()->lastInsertId()); } 
    catch (Throwable $e) { flash('error', 'Error: ' . $e->getMessage()); }
    redirect('tardies.php');
}
