<?php
require_once __DIR__ . '/../core/bootstrap.php';
function search_results($q) {
    $u = current_user(); $res = ['students' => [], 'events' => []]; if (strlen($q) < 2) return $res; $like = '%' . $q . '%';
    if (in_array($u['role'], ['admin', 'secretaria', 'docente'], true)) { $st = db()->prepare("SELECT s.id, CONCAT(s.first_name, ' ', s.last_name) AS name, s.rude, c.name AS course FROM students s LEFT JOIN courses c ON c.id = s.course_id WHERE s.first_name LIKE ? OR s.last_name LIKE ? OR s.rude LIKE ? OR s.ci LIKE ? LIMIT 20"); $st->execute([$like, $like, $like, $like]); $res['students'] = $st->fetchAll(); }
    elseif (($u['role'] ?? '') === 'padre') { $st_ids = array_column((new StudentModel())->children_of($u['id']), 'id'); if (!empty($st_ids)) { $in = implode(',', $st_ids); $st = db()->prepare("SELECT s.id, CONCAT(s.first_name, ' ', s.last_name) AS name, s.rude FROM students s WHERE s.id IN ($in) AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.rude LIKE ?) LIMIT 20"); $st->execute([$like, $like, $like]); $res['students'] = $st->fetchAll(); } }
    elseif (($u['role'] ?? '') === 'estudiante') { $st = (new StudentModel())->by_user($u['id']); if ($st) { $st_q = db()->prepare("SELECT id, CONCAT(first_name, ' ', last_name) AS name, rude FROM students WHERE id = ? AND (first_name LIKE ? OR rude LIKE ?)"); $st_q->execute([$st['id'], $like, $like]); $res['students'] = $st_q->fetchAll(); } }
    $st_ev = db()->prepare("SELECT id, title, event_date FROM calendar_events WHERE title LIKE ? LIMIT 10"); $st_ev->execute([$like]); $res['events'] = $st_ev->fetchAll();
    return $res;
}
