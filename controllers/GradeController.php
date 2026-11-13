<?php
require_once __DIR__ . '/../core/bootstrap.php';

function grades_page() {
    require_perm('grades');
    $u = current_user();
    $courses = [];
    $subjects = [];
    $selected_course = 0;
    $selected_subject = 0;

    // 1. LÓGICA ESPECÍFICA POR ROL
    if (in_array($u['role'], ['admin', 'secretaria'], true)) {
        $courses = db()->query('SELECT id, name, parallel FROM courses ORDER BY level, parallel')->fetchAll();
        $subjects = db()->query('SELECT id, name FROM subjects ORDER BY name')->fetchAll();
        $selected_course = (int)($_GET['course'] ?? ($courses[0]['id'] ?? 0));
        $selected_subject = (int)($_GET['subject'] ?? ($subjects[0]['id'] ?? 0));
        
    } elseif ($u['role'] === 'docente' || is_asesor()) {
        // Obtener SOLO los cursos donde este docente tiene horas asignadas
        $sql_courses = "SELECT DISTINCT c.id, c.name, c.parallel 
                        FROM courses c 
                        INNER JOIN schedules s ON s.course_id = c.id 
                        WHERE s.teacher_user_id = ? 
                        ORDER BY c.name, c.parallel";
        $st = db()->prepare($sql_courses);
        $st->execute([$u['id']]);
        $courses = $st->fetchAll();
        
        // Obtener SOLO las materias que este docente enseña
        $sql_subjects = "SELECT DISTINCT sub.id, sub.name 
                         FROM subjects sub 
                         INNER JOIN schedules s ON s.subject_id = sub.id 
                         WHERE s.teacher_user_id = ? 
                         ORDER BY sub.name";
        $st = db()->prepare($sql_subjects);
        $st->execute([$u['id']]);
        $subjects = $st->fetchAll();

        // Si es asesor, agregar los cursos que asesora (solo lectura)
        if (is_asesor()) {
            $adv_courses = (new UserModel())->get_advised_courses($u['id']);
            // Unir cursos sin duplicados
            $existing_ids = array_column($courses, 'id');
            foreach ($adv_courses as $ac) {
                if (!in_array($ac['id'], $existing_ids)) {
                    $courses[] = $ac;
                }
            }
        }

        $selected_course = (int)($_GET['course'] ?? ($courses[0]['id'] ?? 0));
        $selected_subject = (int)($_GET['subject'] ?? ($subjects[0]['id'] ?? 0));
        
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

    // 2. OBTENER ESTUDIANTES Y NOTAS
    $students = [];
    if ($selected_course > 0) {
        $sql = "SELECT s.id, CONCAT(s.first_name, ' ', s.last_name) AS name 
                FROM students s 
                WHERE s.course_id = ? AND s.status = 1 
                ORDER BY s.last_name";
        $st = db()->prepare($sql);
        $st->execute([$selected_course]);
        $students = $st->fetchAll();

        if (!empty($students)) {
            $ids = implode(',', array_column($students, 'id'));
            
            // Si es docente, forzar que solo vea/edita SU materia
            $sub_cond = ($u['role'] === 'docente' && !is_asesor()) ? " AND g.subject_id = $selected_subject" : "";
            
            $sql_g = "SELECT g.student_id, t.name AS term, g.score, g.observation 
                      FROM grades g 
                      LEFT JOIN terms t ON t.id = g.term_id 
                      WHERE g.student_id IN ($ids) $sub_cond 
                      ORDER BY g.student_id, t.id";
            $grades_raw = db()->query($sql_g)->fetchAll();

            foreach ($students as &$st) {
                $st['grades'] = ['1er Trimestre' => null, '2do Trimestre' => null, '3er Trimestre' => null];
                $st['obs'] = ['1er Trimestre' => '', '2do Trimestre' => '', '3er Trimestre' => ''];
            }
            foreach ($grades_raw as $g) {
                foreach ($students as &$st) {
                    if ($st['id'] == $g['student_id'] && isset($st['grades'][$g['term']])) {
                        $st['grades'][$g['term']] = $g['score'];
                        $st['obs'][$g['term']] = $g['observation'];
                    }
                }
            }
        }
    }

    // Un docente normal puede editar. Un asesor solo puede ver (lectura).
    $can_edit = in_array($u['role'], ['admin', 'secretaria'], true) || ($u['role'] === 'docente' && !is_asesor());

    return [
        'courses' => $courses, 
        'subjects' => $subjects, 
        'selected_course' => $selected_course, 
        'selected_subject' => $selected_subject, 
        'students' => $students, 
        'can_edit' => $can_edit
    ];
}

function grades_save() {
    require_perm('grades');
    verify_csrf();
    $u = current_user();
    
    // Seguridad: Solo admin, secretaria o docente pueden guardar
    if (!in_array($u['role'], ['admin', 'secretaria', 'docente'])) {
        flash('error', 'No tiene permiso para editar calificaciones.');
        redirect('grades.php');
    }

    $course_id = (int)($_POST['course_id'] ?? 0);
    $subject_id = (int)($_POST['subject_id'] ?? 0);
    
    // Validación extra para docentes: no pueden guardar en una materia que no es la suya
    if ($u['role'] === 'docente') {
        $st = db()->prepare("SELECT COUNT(*) as c FROM schedules WHERE teacher_user_id = ? AND subject_id = ? AND course_id = ?");
        $st->execute([$u['id'], $subject_id, $course_id]);
        if ($st->fetch()['c'] == 0) {
            flash('error', 'No puedes calificar una materia que no te corresponde en este curso.');
            redirect('grades.php');
        }
    }

    $scores = $_POST['score'] ?? [];
    $obs = $_POST['observation'] ?? [];

    foreach ($scores as $student_id => $terms) {
        foreach ($terms as $term_name => $score) {
            if ($score !== '') {
                $term_id = $term_name === '1er Trimestre' ? 1 : ($term_name === '2do Trimestre' ? 2 : 3);
                $observation = trim($obs[$student_id][$term_name] ?? '');
                
                try {
                    db()->prepare("INSERT INTO grades (student_id, subject_id, term_id, score, observation, created_by) 
                                   VALUES (?, ?, ?, ?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE score=VALUES(score), observation=VALUES(observation)")
                        ->execute([(int)$student_id, $subject_id, $term_id, (float)$score, $observation, $u['id']]);
                } catch (Throwable $e) {
                    flash('error', 'Error al guardar notas.');
                    redirect('grades.php?course=' . $course_id . '&subject=' . $subject_id);
                }
            }
        }
    }
    flash('success', 'Calificaciones guardadas correctamente.');
    redirect('grades.php?course=' . $course_id . '&subject=' . $subject_id);
}