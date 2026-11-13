<?php
require_once __DIR__ . '/../core/bootstrap.php';
function calendar_page() {
    require_perm('calendar');
    $events = db()->query("SELECT ce.*, c.name AS course_name FROM calendar_events ce LEFT JOIN courses c ON c.id = ce.target_course_id ORDER BY ce.event_date DESC")->fetchAll();
    $courses = db()->query("SELECT id, name, parallel FROM courses ORDER BY name, parallel")->fetchAll();
    return ['events' => $events, 'courses' => $courses];
}
function calendar_save() {
    require_perm('calendar'); verify_csrf(); $u = current_user();
    $data = ['title' => trim($_POST['title'] ?? ''), 'description' => trim($_POST['description'] ?? ''), 'event_date' => $_POST['event_date'] ?? null, 'event_type' => $_POST['event_type'] ?? 'otro', 'target_course_id' => (int)($_POST['target_course_id'] ?? 0) ?: null, 'responsible' => trim($_POST['responsible'] ?? ''), 'created_by' => $u['id']];
    try { $cols = array_keys($data); $sql = 'INSERT INTO calendar_events (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')'; db()->prepare($sql)->execute($data); flash('success', 'Evento creado.'); } 
    catch (Throwable $e) { flash('error', 'Error: ' . $e->getMessage()); }
    redirect('calendar.php');
}
function calendar_delete($id) {
    require_perm('calendar'); verify_csrf();
    try { db()->prepare('DELETE FROM calendar_events WHERE id = ?')->execute([(int)$id]); flash('success', 'Evento eliminado.'); } 
    catch (Throwable $e) { flash('error', 'Error.'); }
    redirect('calendar.php');
}
