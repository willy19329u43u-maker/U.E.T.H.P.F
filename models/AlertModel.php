<?php
require_once __DIR__ . '/Model.php';

class AlertModel extends Model {
    protected $table = 'student_alerts';
    
    /**
     * Analiza estudiantes en riesgo basado en asistencia y calificaciones
     */
    public function analyzeAtRiskStudents() {
        $alerts = [];
        
        // 1. Estudiantes con muchas faltas
        $sql = "SELECT s.id, CONCAT(s.first_name, ' ', s.last_name) AS name, s.course_id,
                       COUNT(CASE WHEN a.status = 'F' THEN 1 END) AS faltas,
                       COUNT(CASE WHEN a.status = 'R' THEN 1 END) AS retrasos
                FROM students s
                LEFT JOIN attendance a ON a.student_id = s.id 
                    AND a.attendance_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                WHERE s.status = 1
                GROUP BY s.id
                HAVING faltas >= 3 OR retrasos >= 5";
        
        $st = db()->prepare($sql);
        $st->execute();
        $attendanceIssues = $st->fetchAll();
        
        foreach ($attendanceIssues as $student) {
            $alerts[] = [
                'student_id' => $student['id'],
                'student_name' => $student['name'],
                'alert_type' => 'attendance',
                'severity' => $student['faltas'] >= 5 ? 'high' : 'medium',
                'message' => "Acumula {$student['faltas']} faltas y {$student['retrasos']} retrasos en el último mes",
                'course_id' => $student['course_id']
            ];
        }
        
        // 2. Estudiantes con promedio bajo o en descenso
        $sql = "SELECT s.id, CONCAT(s.first_name, ' ', s.last_name) AS name, s.course_id,
                       AVG(g.score) AS avg_score,
                       (SELECT AVG(g2.score) FROM grades g2 
                        WHERE g2.student_id = s.id AND g2.term_id < g.term_id) AS prev_avg
                FROM students s
                INNER JOIN grades g ON g.student_id = s.id
                WHERE s.status = 1
                GROUP BY s.id, g.term_id
                HAVING avg_score < 60 OR (prev_avg IS NOT NULL AND avg_score < prev_avg - 15)";
        
        $st = db()->prepare($sql);
        $st->execute();
        $gradeIssues = $st->fetchAll();
        
        foreach ($gradeIssues as $student) {
            $alerts[] = [
                'student_id' => $student['id'],
                'student_name' => $student['name'],
                'alert_type' => 'grades',
                'severity' => $student['avg_score'] < 51 ? 'high' : 'medium',
                'message' => "Promedio actual: {$student['avg_score']}" . 
                            ($student['prev_avg'] ? ", bajó desde {$student['prev_avg']}" : ''),
                'course_id' => $student['course_id']
            ];
        }
        
        return $alerts;
    }
    
    /**
     * Guarda alertas en la base de datos
     */
    public function saveAlerts($alerts) {
        foreach ($alerts as $alert) {
            try {
                $sql = "INSERT INTO student_alerts (student_id, alert_type, severity, message, course_id, created_at) 
                        VALUES (?, ?, ?, ?, ?, NOW()) 
                        ON DUPLICATE KEY UPDATE severity=VALUES(severity), message=VALUES(message)";
                db()->prepare($sql)->execute([
                    $alert['student_id'],
                    $alert['alert_type'],
                    $alert['severity'],
                    $alert['message'],
                    $alert['course_id']
                ]);
            } catch (Throwable $e) {
                // Ignorar errores de duplicados
            }
        }
    }
    
    /**
     * Obtiene alertas activas para un estudiante
     */
    public function getStudentAlerts($studentId) {
        $st = db()->prepare("SELECT * FROM student_alerts WHERE student_id = ? ORDER BY created_at DESC");
        $st->execute([(int)$studentId]);
        return $st->fetchAll();
    }
}