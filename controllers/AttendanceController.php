<?php
require_once __DIR__ . '/../core/bootstrap.php';
function attendance_page() {
    require_perm('attendance');
    $u = current_user(); $courses = [];
    if (in_array($u['role'], ['admin', 'secretaria'], true)) { $courses = db()->query('SELECT id, name, parallel FROM courses ORDER BY level, parallel')->fetchAll(); } 
    elseif ($u['role'] === 'docente' || is_asesor()) { $courses = (new UserModel())->get_taught_courses($u['id']); if (is_asesor()) { $adv = (new UserModel())->get_advised_courses($u['id']); $courses = array_merge($courses, $adv); } }
    elseif ($u['role'] === 'padre') { $st_ids = array_column((new StudentModel())->children_of($u['id']), 'course_id'); if (!empty($st_ids)) { $in = implode(',', array_unique($st_ids)); $courses = db()->query("SELECT id, name, parallel FROM courses WHERE id IN ($in)")->fetchAll(); } }
    elseif ($u['role'] === 'estudiante') { $st = (new StudentModel())->by_user($u['id']); if ($st) $courses = db()->query("SELECT id, name, parallel FROM courses WHERE id = " . (int)$st['course_id'])->fetchAll(); }
    $selected_course = (int)($_GET['course'] ?? ($courses[0]['id'] ?? 0)); $date = $_GET['date'] ?? date('Y-m-d');
    $students = []; $existing = [];
    if ($selected_course > 0) {
        $sql = "SELECT s.id, CONCAT(s.first_name, ' ', s.last_name) AS name, s.rude FROM students s WHERE s.course_id = ? AND s.status = 1 ORDER BY s.last_name";
        $st = db()->prepare($sql); $st->execute([$selected_course]); $students = $st->fetchAll();
        if (!empty($students)) { $ids = array_column($students, 'id'); $in = implode(',', $ids); $st2 = db()->prepare("SELECT student_id, status, reason FROM attendance WHERE student_id IN ($in) AND attendance_date = ?"); $st2->execute([$date]); foreach ($st2->fetchAll() as $row) { $existing[$row['student_id']] = $row; } }
    }
    return ['courses' => $courses, 'selected_course' => $selected_course, 'date' => $date, 'students' => $students, 'existing' => $existing];
}
function attendance_save() {
    require_perm('attendance'); verify_csrf(); $u = current_user();
    $course_id = (int)($_POST['course_id'] ?? 0); $date = $_POST['date'] ?? date('Y-m-d'); $statuses = $_POST['status'] ?? []; $reasons = $_POST['reason'] ?? [];
    $am = new AttendanceModel();
    foreach ($statuses as $student_id => $status) {
        $reason = trim($reasons[$student_id] ?? '');
        if (in_array($status, ['L', 'RC']) && empty($reason)) { flash('error', "El motivo es obligatorio para el estado '$status' del estudiante ID $student_id."); redirect('attendance.php?course=' . $course_id . '&date=' . $date); }
        if ($status === 'L' && ($u['role'] ?? '') !== 'secretaria' && ($u['role'] ?? '') !== 'admin') { flash('error', "Solo la secretaría puede registrar 'Licencia'."); redirect('attendance.php?course=' . $course_id . '&date=' . $date); }
        $am->register((int)$student_id, $date, $status, $reason, '', $u['id']);
    }
    flash('success', 'Asistencia registrada correctamente.'); redirect('attendance.php?course=' . $course_id . '&date=' . $date);
}
