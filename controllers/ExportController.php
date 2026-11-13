<?php
/**
 * ExportController - Exportación de reportes a CSV y Excel (HTML-based .xls compatible).
 * No requiere librerías externas; funciona con PHP nativo.
 */
require_once __DIR__ . '/../core/bootstrap.php';

function export_csv() {
    require_login();
    if (!can('reports')) { http_response_code(403); exit('Acceso denegado.'); }
    $tipo = $_GET['tipo'] ?? 'students';
    $filename = 'reporte_' . $tipo . '_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    $out = fopen('php://output', 'w');
    fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM para Excel
    if ($tipo === 'students') {
        fputcsv($out, ['ID','Nombres','Apellidos','RUDE','CI','Curso','Tutor','Teléfono','Estado'], ';');
        $st = db()->query("SELECT s.*, c.name AS course_name FROM students s LEFT JOIN courses c ON c.id = s.course_id ORDER BY s.last_name");
        while ($s = $st->fetch()) {
            $course = $s['course_name'] ?: '-';
            $guardian = $s['guardian_name'] ?: '-';
            $phone = $s['guardian_phone'] ?: '-';
            fputcsv($out, [$s['id'], $s['first_name'], $s['last_name'], $s['rude'], $s['ci'], $course, $guardian, $phone, $s['status'] ? 'Activo' : 'Inactivo'], ';');
        }
    } elseif ($tipo === 'grades') {
        fputcsv($out, ['ID','Estudiante','Materia','Trimestre','Nota','Observación'], ';');
        $st = db()->query("SELECT g.id, CONCAT(s.first_name,' ',s.last_name) AS student, sub.name AS subject, t.name AS term_name, g.score, g.observation FROM grades g INNER JOIN students s ON s.id = g.student_id INNER JOIN subjects sub ON sub.id = g.subject_id LEFT JOIN terms t ON t.id = g.term_id ORDER BY student");
        while ($g = $st->fetch()) {
            $obs = $g['observation'] ?: '';
            fputcsv($out, [$g['id'], $g['student'], $g['subject'], $g['term_name'], $g['score'], $obs], ';');
        }
    } elseif ($tipo === 'attendance') {
        fputcsv($out, ['ID','Estudiante','Fecha','Estado','Motivo'], ';');
        $st = db()->query("SELECT a.id, CONCAT(s.first_name,' ',s.last_name) AS student, a.attendance_date, a.status, a.reason FROM attendance a INNER JOIN students s ON s.id = a.student_id ORDER BY a.attendance_date DESC");
        while ($a = $st->fetch()) {
            $reason = $a['reason'] ?: '';
            fputcsv($out, [$a['id'], $a['student'], $a['attendance_date'], $a['status'], $reason], ';');
        }
    }
    fclose($out);
    exit;
}

function export_xls() {
    require_login();
    if (!can('reports')) { http_response_code(403); exit('Acceso denegado.'); }
    $tipo = $_GET['tipo'] ?? 'students';
    $filename = 'reporte_' . $tipo . '_' . date('Ymd_His') . '.xls';
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "<html><head><meta charset='utf-8'><title>Reporte $tipo</title></head><body>";
    if ($tipo === 'students') {
        echo "<table border='1'><tr><th>ID</th><th>Nombres</th><th>Apellidos</th><th>RUDE</th><th>CI</th><th>Curso</th><th>Tutor</th><th>Teléfono</th><th>Estado</th></tr>";
        $st = db()->query("SELECT s.*, c.name AS course_name FROM students s LEFT JOIN courses c ON c.id = s.course_id ORDER BY s.last_name");
        while ($s = $st->fetch()) {
            $course = $s['course_name'] ?: '-';
            $guardian = $s['guardian_name'] ?: '-';
            $phone = $s['guardian_phone'] ?: '-';
            $estado = $s['status'] ? 'Activo' : 'Inactivo';
            echo "<tr><td>{$s['id']}</td><td>{$s['first_name']}</td><td>{$s['last_name']}</td><td>{$s['rude']}</td><td>{$s['ci']}</td><td>{$course}</td><td>{$guardian}</td><td>{$phone}</td><td>{$estado}</td></tr>";
        }
        echo "</table>";
    } elseif ($tipo === 'grades') {
        echo "<table border='1'><tr><th>ID</th><th>Estudiante</th><th>Materia</th><th>Trimestre</th><th>Nota</th><th>Observación</th></tr>";
        $st = db()->query("SELECT g.id, CONCAT(s.first_name,' ',s.last_name) AS student, sub.name AS subject, t.name AS term_name, g.score, g.observation FROM grades g INNER JOIN students s ON s.id = g.student_id INNER JOIN subjects sub ON sub.id = g.subject_id LEFT JOIN terms t ON t.id = g.term_id ORDER BY student");
        while ($g = $st->fetch()) {
            $obs = $g['observation'] ?: '';
            echo "<tr><td>{$g['id']}</td><td>{$g['student']}</td><td>{$g['subject']}</td><td>{$g['term_name']}</td><td>{$g['score']}</td><td>{$obs}</td></tr>";
        }
        echo "</table>";
    } elseif ($tipo === 'attendance') {
        echo "<table border='1'><tr><th>ID</th><th>Estudiante</th><th>Fecha</th><th>Estado</th><th>Motivo</th></tr>";
        $st = db()->query("SELECT a.id, CONCAT(s.first_name,' ',s.last_name) AS student, a.attendance_date, a.status, a.reason FROM attendance a INNER JOIN students s ON s.id = a.student_id ORDER BY a.attendance_date DESC");
        while ($a = $st->fetch()) {
            $reason = $a['reason'] ?: '';
            echo "<tr><td>{$a['id']}</td><td>{$a['student']}</td><td>{$a['attendance_date']}</td><td>{$a['status']}</td><td>{$reason}</td></tr>";
        }
        echo "</table>";
    }
    echo "</body></html>";
    exit;
}
