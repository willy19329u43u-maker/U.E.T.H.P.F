<?php
require_once __DIR__ . '/../core/bootstrap.php';
function reports_page() {
    require_perm('reports'); $type = $_GET['type'] ?? 'attendance'; $from = $_GET['from'] ?? date('Y-m-01'); $to = $_GET['to'] ?? date('Y-m-d'); $rows = [];
    try {
        if ($type === 'attendance') { $st = db()->prepare("SELECT a.attendance_date, CONCAT(s.first_name,' ',s.last_name) AS student, a.status, a.reason FROM attendance a INNER JOIN students s ON s.id=a.student_id WHERE a.attendance_date BETWEEN ? AND ? ORDER BY a.attendance_date DESC"); $st->execute([$from, $to]); $rows = $st->fetchAll(); }
        elseif ($type === 'tardies') { $st = db()->prepare("SELECT t.tardy_date, CONCAT(s.first_name,' ',s.last_name) AS student, t.arrival_time, t.status FROM tardies t INNER JOIN students s ON s.id=t.student_id WHERE t.tardy_date BETWEEN ? AND ? ORDER BY t.tardy_date DESC"); $st->execute([$from, $to]); $rows = $st->fetchAll(); }
        elseif ($type === 'grades') { $rows = db()->query("SELECT CONCAT(s.first_name,' ',s.last_name) AS student, sub.name AS subject, t.name AS term, g.score FROM grades g INNER JOIN students s ON s.id=g.student_id INNER JOIN subjects sub ON sub.id=g.subject_id LEFT JOIN terms t ON t.id=g.term_id ORDER BY g.id DESC")->fetchAll(); }
    } catch (Throwable $e) { flash('error', 'Error en reporte.'); }
    return ['type' => $type, 'from' => $from, 'to' => $to, 'rows' => $rows];
}
function reports_export_csv() {
    require_perm('reports'); $type = $_GET['type'] ?? 'report'; $data = reports_page();
    header('Content-Type: text/csv; charset=utf-8'); header('Content-Disposition: attachment; filename="reporte_' . $type . '.csv"');
    $out = fopen('php://output', 'w'); fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
    if (!empty($data['rows'])) { fputcsv($out, array_keys($data['rows'][0])); foreach ($data['rows'] as $r) fputcsv($out, $r); }
    fclose($out); exit;
}
