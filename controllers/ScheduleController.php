<?php
/* ============================================================================
 * SCHEDULE CONTROLLER v5 - Gestión de horarios (listar, crear, eliminar)
 * ==========================================================================*/
require_once __DIR__ . '/../core/bootstrap.php';

/**
 * Obtiene los horarios según el rol del usuario.
 */
function schedule_page() {
    require_perm('schedules');
    $u = current_user();
    $courses = [];
    $selected_course = 0;
    $schedules = [];

    if (in_array($u['role'], ['admin', 'secretaria'], true)) {
        $courses = db()->query('SELECT id, name, parallel FROM courses ORDER BY level, parallel')->fetchAll();
        $selected_course = (int)($_GET['course'] ?? ($courses[0]['id'] ?? 0));
    } elseif ($u['role'] === 'docente' || is_asesor()) {
        if (is_asesor()) {
            $courses = (new UserModel())->get_advised_courses($u['id']);
            $selected_course = (int)($_GET['course'] ?? ($courses[0]['id'] ?? 0));
        } else {
            $courses = (new UserModel())->get_taught_courses($u['id']);
            $selected_course = 0;
        }
    } elseif ($u['role'] === 'padre') {
        $st_ids = array_column((new StudentModel())->children_of($u['id']), 'course_id');
        if (!empty($st_ids)) {
            $in = implode(',', array_unique($st_ids));
            $courses = db()->query("SELECT id, name, parallel FROM courses WHERE id IN ($in)")->fetchAll();
            $selected_course = $courses[0]['id'] ?? 0;
        }
    } elseif ($u['role'] === 'estudiante') {
        $st = (new StudentModel())->by_user($u['id']);
        if ($st) {
            $courses = db()->query("SELECT id, name, parallel FROM courses WHERE id = " . (int)$st['course_id'])->fetchAll();
            $selected_course = $st['course_id'];
        }
    }

    if ($selected_course > 0) {
        $sql = "SELECT sc.id, sc.day_of_week, sc.start_time, sc.end_time, sub.name AS subject, 
                       u.full_name AS teacher, sc.classroom 
                FROM schedules sc 
                INNER JOIN subjects sub ON sub.id = sc.subject_id 
                LEFT JOIN users u ON u.id = sc.teacher_user_id 
                WHERE sc.course_id = ? 
                ORDER BY FIELD(sc.day_of_week, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'), sc.start_time";
        $st = db()->prepare($sql);
        $st->execute([$selected_course]);
        $schedules = $st->fetchAll();
    } elseif ($u['role'] === 'docente' && !is_asesor()) {
        $sql = "SELECT sc.id, sc.day_of_week, sc.start_time, sc.end_time, sub.name AS subject, 
                       c.name AS course, c.parallel, sc.classroom 
                FROM schedules sc 
                INNER JOIN subjects sub ON sub.id = sc.subject_id 
                INNER JOIN courses c ON c.id = sc.course_id 
                WHERE sc.teacher_user_id = ? 
                ORDER BY FIELD(sc.day_of_week, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'), sc.start_time";
        $st = db()->prepare($sql);
        $st->execute([$u['id']]);
        $schedules = $st->fetchAll();
    }

    return [
        'courses' => $courses,
        'selected_course' => $selected_course,
        'schedules' => $schedules,
        'is_teacher_only' => ($u['role'] === 'docente' && !is_asesor())
    ];
}

/**
 * Crea un nuevo horario (solo admin/secretaría).
 */
function schedule_save() {
    require_perm('schedules');
    verify_csrf();

    $data = [
        'course_id' => (int)($_POST['course_id'] ?? 0),
        'subject_id' => (int)($_POST['subject_id'] ?? 0),
        'teacher_user_id' => (int)($_POST['teacher_user_id'] ?? 0) ?: null,
        'day_of_week' => $_POST['day_of_week'] ?? '',
        'start_time' => $_POST['start_time'] ?? '',
        'end_time' => $_POST['end_time'] ?? '',
        'classroom' => trim($_POST['classroom'] ?? '')
    ];

    // Validar conflictos antes de guardar
    $conflicts = checkScheduleConflicts($data);
    
    if (!empty($conflicts)) {
        $errorMsg = '⚠️ Conflictos detectados: ' . implode('; ', $conflicts);
        flash('error', $errorMsg);
        redirect('schedules.php');
    }

    try {
        $cols = array_keys($data);
        $sql = 'INSERT INTO schedules (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')';
        db()->prepare($sql)->execute($data);
        flash('success', 'Horario agregado correctamente.');
        audit('create_schedule', 'schedules', (int)db()->lastInsertId());
    } catch (Throwable $e) {
        flash('error', 'Error: ' . $e->getMessage());
    }
    redirect('schedules.php');
}

/**
 * Verifica conflictos de horarios
 */
function checkScheduleConflicts($newSchedule) {
    $conflicts = [];
    
    if (empty($newSchedule['teacher_user_id']) || empty($newSchedule['day_of_week'])) {
        return $conflicts;
    }
    
    // 1. Conflicto de docente (mismo profesor, mismo día, horario superpuesto)
    $sql = "SELECT s.*, sub.name AS subject_name, c.name AS course_name, c.parallel 
            FROM schedules s 
            INNER JOIN subjects sub ON sub.id = s.subject_id 
            INNER JOIN courses c ON c.id = s.course_id 
            WHERE s.teacher_user_id = ? 
            AND s.day_of_week = ? 
            AND s.id != ?
            AND ((s.start_time < ? AND s.end_time > ?) OR (s.start_time < ? AND s.end_time > ?))";
    
    $st = db()->prepare($sql);
    $st->execute([
        $newSchedule['teacher_user_id'],
        $newSchedule['day_of_week'],
        0, // ID del nuevo horario (0 porque aún no existe)
        $newSchedule['end_time'],
        $newSchedule['start_time'],
        $newSchedule['start_time'],
        $newSchedule['end_time']
    ]);
    
    $conflictingSchedules = $st->fetchAll();
    
    foreach ($conflictingSchedules as $conflict) {
        $conflicts[] = "El docente ya tiene '{$conflict['subject_name']}' en {$conflict['course_name']} {$conflict['parallel']} de {$conflict['start_time']} a {$conflict['end_time']}";
    }
    
    // 2. Conflicto de aula (misma aula, mismo día, horario superpuesto)
    if (!empty($newSchedule['classroom'])) {
        $sql = "SELECT s.*, sub.name AS subject_name, c.name AS course_name, c.parallel 
                FROM schedules s 
                INNER JOIN subjects sub ON sub.id = s.subject_id 
                INNER JOIN courses c ON c.id = s.course_id 
                WHERE s.classroom = ? 
                AND s.day_of_week = ? 
                AND s.id != ?
                AND ((s.start_time < ? AND s.end_time > ?) OR (s.start_time < ? AND s.end_time > ?))";
        
        $st = db()->prepare($sql);
        $st->execute([
            $newSchedule['classroom'],
            $newSchedule['day_of_week'],
            0,
            $newSchedule['end_time'],
            $newSchedule['start_time'],
            $newSchedule['start_time'],
            $newSchedule['end_time']
        ]);
        
        $conflictingClassrooms = $st->fetchAll();
        
        foreach ($conflictingClassrooms as $conflict) {
            $conflicts[] = "El aula '{$conflict['classroom']}' ya está ocupada por '{$conflict['subject_name']}' de {$conflict['course_name']} {$conflict['parallel']}";
        }
    }
    
    return $conflicts;
}

/**
 * Elimina un horario por ID (solo admin/secretaría).
 */
function schedule_delete($id) {
    require_perm('schedules');
    verify_csrf();

    try {
        db()->prepare('DELETE FROM schedules WHERE id = ?')->execute([(int)$id]);
        flash('success', 'Horario eliminado.');
        audit('delete_schedule', 'schedules', (int)$id);
    } catch (Throwable $e) {
        flash('error', 'Error al eliminar.');
    }
    redirect('schedules.php');
}