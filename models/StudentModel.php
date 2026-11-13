<?php
require_once __DIR__ . '/Model.php';
class StudentModel extends Model {
    protected $table = 'students';
    public function children_of($user_id) { $st = db()->prepare('SELECT s.* FROM students s INNER JOIN user_students us ON us.student_id = s.id WHERE us.user_id = ?'); $st->execute([(int)$user_id]); return $st->fetchAll(); }
    public function by_user($user_id) { $st = db()->prepare('SELECT * FROM students WHERE user_id = ? LIMIT 1'); $st->execute([(int)$user_id]); return $st->fetch(); }
    public function attendance_of($student_id, $limit = 50) { $st = db()->prepare('SELECT attendance_date, status, reason, observation FROM attendance WHERE student_id = ? ORDER BY attendance_date DESC LIMIT ' . (int)$limit); $st->execute([(int)$student_id]); return $st->fetchAll(); }
    public function grades_of($student_id) { $st = db()->prepare('SELECT g.id, sub.name AS subject, t.name AS term_name, g.score, g.observation FROM grades g INNER JOIN subjects sub ON sub.id = g.subject_id LEFT JOIN terms t ON t.id = g.term_id WHERE g.student_id = ? ORDER BY sub.name, t.id'); $st->execute([(int)$student_id]); return $st->fetchAll(); }
    public function full_data($student_id) { $st = db()->prepare('SELECT s.*, c.name AS curso FROM students s LEFT JOIN courses c ON c.id = s.course_id WHERE s.id = ?'); $st->execute([(int)$student_id]); return $st->fetch(); }
    public function stats_of($student_id) { $st = db()->prepare('SELECT * FROM v_attendance_stats WHERE student_id = ?'); $st->execute([(int)$student_id]); return $st->fetch(); }
    public function tardies_of($student_id) { $st = db()->prepare('SELECT tardy_date, arrival_time, status, justification FROM tardies WHERE student_id = ? ORDER BY tardy_date DESC LIMIT 50'); $st->execute([(int)$student_id]); return $st->fetchAll(); }
}
