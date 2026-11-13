<?php
require_once __DIR__ . '/../core/bootstrap.php';
function announcement_page() {
    require_perm('announcements'); $u = current_user();
    $courses = db()->query('SELECT id, name, parallel FROM courses ORDER BY level, parallel')->fetchAll();
    $students = []; $subjects = db()->query('SELECT id, name FROM subjects ORDER BY name')->fetchAll();
    if (in_array($u['role'], ['admin', 'secretaria'], true)) { $students = db()->query("SELECT id, CONCAT(first_name, ' ', last_name) AS name FROM students ORDER BY last_name")->fetchAll(); }
    return ['courses' => $courses, 'students' => $students, 'subjects' => $subjects];
}
function announcement_save() {
    require_perm('announcements'); verify_csrf(); $u = current_user();
    $title = trim($_POST['title'] ?? ''); $content = trim($_POST['content'] ?? ''); $target_type = $_POST['target_type'] ?? 'global';
    $course_id = !empty($_POST['course_id']) ? (int)$_POST['course_id'] : null; $student_id = !empty($_POST['student_id']) ? (int)$_POST['student_id'] : null; $subject_id = !empty($_POST['subject_id']) ? (int)$_POST['subject_id'] : null;
    if (empty($title) || empty($content)) { flash('error', 'Título y contenido son obligatorios.'); redirect('announcements.php'); }
    try { db()->prepare("INSERT INTO announcements (title, content, target_type, course_id, student_id, subject_id, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())")->execute([$title, $content, $target_type, $course_id, $student_id, $subject_id, $u['id']]); flash('success', 'Aviso publicado.'); } 
    catch (Throwable $e) { flash('error', 'Error al publicar.'); }
    redirect('announcements.php');
}
function announcement_delete($id) {
    require_perm('announcements'); verify_csrf();
    try { db()->prepare('DELETE FROM announcements WHERE id = ?')->execute([(int)$id]); flash('success', 'Aviso eliminado.'); } 
    catch (Throwable $e) { flash('error', 'Error al eliminar.'); }
    redirect('announcements.php');
}
