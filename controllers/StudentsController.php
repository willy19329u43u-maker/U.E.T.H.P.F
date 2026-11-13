<?php
require_once __DIR__ . '/../core/bootstrap.php';
function students_page() {
    require_perm('students');
    $courses = db()->query('SELECT id, name, parallel FROM courses ORDER BY level, parallel')->fetchAll();
    $users = db()->query("SELECT id, username FROM users WHERE role='estudiante' ORDER BY username")->fetchAll();
    $page = (int)($_GET['page'] ?? 1);
    $perPage = (int)($_GET['per_page'] ?? 25);
    $search = trim($_GET['q'] ?? '');
    $where = 'WHERE 1=1';
    $params = [];
    if ($search !== '') {
        $where .= ' AND (first_name LIKE :q OR last_name LIKE :q OR rude LIKE :q OR ci LIKE :q)';
        $params[':q'] = '%' . $search . '%';
    }
    $countSql = 'SELECT COUNT(*) c FROM students ' . $where;
    $st = db()->prepare($countSql);
    foreach ($params as $k => $v) $st->bindValue($k, $v, \PDO::PARAM_STR);
    $st->execute();
    $total = (int)$st->fetch()['c'];
    $paginator = new \Paginator($total, $page, $perPage);
    $sql = "SELECT s.*, c.name AS course_name FROM students s LEFT JOIN courses c ON c.id = s.course_id $where ORDER BY s.last_name LIMIT :limit OFFSET :offset";
    $st = db()->prepare($sql);
    foreach ($params as $k => $v) $st->bindValue($k, $v, \PDO::PARAM_STR);
    $st->bindValue(':limit', $paginator->getPerPage(), \PDO::PARAM_INT);
    $st->bindValue(':offset', $paginator->getOffset(), \PDO::PARAM_INT);
    $st->execute();
    $students = $st->fetchAll();
    return ['courses' => $courses, 'users' => $users, 'students' => $students, 'paginator' => $paginator, 'search' => $search];
}
function students_save() {
    require_perm('students'); verify_csrf();
    $id = (int)($_POST['id'] ?? 0);
    $data = ['first_name' => trim($_POST['first_name'] ?? ''), 'last_name' => trim($_POST['last_name'] ?? ''), 'rude' => trim($_POST['rude'] ?? ''), 'ci' => trim($_POST['ci'] ?? ''), 'birth_date' => $_POST['birth_date'] ?? null, 'gender' => $_POST['gender'] ?? null, 'address' => trim($_POST['address'] ?? ''), 'enrollment_year' => (int)($_POST['enrollment_year'] ?? 0), 'course_id' => (int)($_POST['course_id'] ?? 0), 'user_id' => (int)($_POST['user_id'] ?? 0) ?: null, 'guardian_name' => trim($_POST['guardian_name'] ?? ''), 'guardian_phone' => trim($_POST['guardian_phone'] ?? ''), 'guardian_email' => trim($_POST['guardian_email'] ?? ''), 'observations' => trim($_POST['observations'] ?? ''), 'status' => (int)($_POST['status'] ?? 1)];
    try {
        if ($id > 0) { $sets = []; foreach ($data as $k => $v) $sets[] = "$k = :$k"; $data['id'] = $id; db()->prepare('UPDATE students SET ' . implode(', ', $sets) . ' WHERE id = :id')->execute($data); flash('success', 'Estudiante actualizado.'); } 
        else { $cols = array_keys($data); $sql = 'INSERT INTO students (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')'; db()->prepare($sql)->execute($data); flash('success', 'Estudiante creado.'); }
        audit($id > 0 ? 'update_student' : 'create_student', 'students', $id);
    } catch (Throwable $e) { flash('error', 'Error: ' . $e->getMessage()); }
    redirect('students.php');
}
function students_delete($id) {
    require_perm('students'); verify_csrf();
    try { db()->prepare('DELETE FROM students WHERE id = ?')->execute([(int)$id]); flash('success', 'Estudiante eliminado.'); } 
    catch (Throwable $e) { flash('error', 'Error al eliminar.'); }
    redirect('students.php');
}
