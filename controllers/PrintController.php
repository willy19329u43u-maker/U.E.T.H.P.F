<?php
require_once __DIR__ . '/../core/bootstrap.php';
function print_libreta_data($student_id) {
    require_login(); $u = current_user(); $sm = new StudentModel(); $student = $sm->full_data($student_id); if (!$student) return null;
    $allowed = false;
    if (in_array($u['role'], ['admin', 'secretaria'], true)) $allowed = true;
    elseif (($u['role'] ?? '') === 'padre') { $kids = $sm->children_of($u['id']); $allowed = in_array($student_id, array_column($kids, 'id')); }
    elseif (($u['role'] ?? '') === 'estudiante') { $my_st = $sm->by_user($u['id']); $allowed = $my_st && $my_st['id'] == $student_id; }
    elseif (($u['role'] ?? '') === 'docente' || is_asesor()) { if (is_asesor()) { $adv = (new UserModel())->get_advised_courses($u['id']); $allowed = in_array($student['course_id'], array_column($adv, 'id')); } else { $taught = (new UserModel())->get_taught_courses($u['id']); $allowed = in_array($student['course_id'], array_column($taught, 'id')); } }
    if (!$allowed) return null;
    return ['student' => $student, 'stats' => $sm->stats_of($student_id), 'grades' => $sm->grades_of($student_id), 'attendance' => $sm->attendance_of($student_id, 100)];
}
function print_lista_data($course_id, $date) {
    require_perm('reports');
    $sql = "SELECT s.id, CONCAT(s.first_name, ' ', s.last_name) AS name, s.rude, s.ci, COALESCE(a.status, 'SIN REGISTRO') AS status, a.reason FROM students s LEFT JOIN attendance a ON a.student_id = s.id AND a.attendance_date = ? WHERE s.course_id = ? AND s.status = 1 ORDER BY s.last_name, s.first_name";
    $st = db()->prepare($sql); $st->execute([$date, (int)$course_id]); $rows = $st->fetchAll();
    $course = db()->prepare('SELECT name, parallel FROM courses WHERE id = ?'); $course->execute([(int)$course_id]); $c = $course->fetch();
    return ['rows' => $rows, 'course' => $c ?: ['name' => 'Desconocido', 'parallel' => ''], 'date' => $date];
}
