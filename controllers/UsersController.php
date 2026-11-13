<?php
/* ============================================================================
 * USERS CONTROLLER v5 - Gestión completa de usuarios (listar, crear, editar, eliminar)
 * ==========================================================================*/
require_once __DIR__ . '/../core/bootstrap.php';

/**
 * Obtiene la lista de usuarios y datos necesarios para la vista.
 * @return array
 */
function users_page() {
    require_perm('users');
    $users = db()->query('SELECT * FROM users ORDER BY id DESC')->fetchAll();
    return ['users' => $users];
}

/**
 * Guarda o actualiza un usuario según si viene con ID o no.
 */
function users_save() {
    require_perm('users'); 
    verify_csrf(); 
    
    $id = (int)($_POST['id'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $role = $_POST['role'] ?? 'estudiante';
    $password = trim($_POST['password'] ?? '');
    $is_asesor = isset($_POST['is_asesor']) ? 1 : 0;
    $advisor_course_id = (int)($_POST['advisor_course_id'] ?? 0);
    
    if (empty($username) || empty($role)) {
        flash('error', 'Usuario y Rol son obligatorios.');
        redirect('users.php');
    }
    if ($id === 0 && empty($password)) {
        flash('error', 'La contraseña es obligatoria para nuevos usuarios.');
        redirect('users.php');
    }

    $data = [
        'username' => $username,
        'role' => $role,
        'email' => trim($_POST['email'] ?? ''),
        'full_name' => trim($_POST['full_name'] ?? ''),
        'active' => (int)($_POST['active'] ?? 1),
        'is_asesor' => $is_asesor // Agregado al array de datos
    ];
    
    try {
        $new_user_id = $id;
        if ($id > 0) {
            $sets = []; 
            foreach ($data as $k => $v) $sets[] = "$k = :$k"; 
            if (!empty($password)) { 
                $sets[] = "password_hash = :hash"; 
                $data['hash'] = create_password_hash($password); 
            } 
            $data['id'] = $id; 
            db()->prepare('UPDATE users SET ' . implode(', ', $sets) . ' WHERE id = :id')->execute($data); 
            flash('success', 'Usuario actualizado correctamente.'); 
        } else {
            $data['password_hash'] = create_password_hash($password); 
            $cols = array_keys($data); 
            $sql = 'INSERT INTO users (' . implode(',', $cols) . ') VALUES (:' . implode(',:', $cols) . ')'; 
            db()->prepare($sql)->execute($data); 
            $new_user_id = (int)db()->lastInsertId(); // Guardar el ID del nuevo usuario
            flash('success', 'Usuario creado correctamente.'); 
        }
        
        // LÓGICA DE ASESORES (Actualizar tabla course_advisors)
        if ($role === 'docente') {
            if ($is_asesor && $advisor_course_id > 0) {
                // Verificar si ya tiene registro de asesor
                $st = db()->prepare("SELECT id FROM course_advisors WHERE user_id = ?");
                $st->execute([$new_user_id]);
                $existing = $st->fetch();
                
                if ($existing) {
                    db()->prepare("UPDATE course_advisors SET course_id = ? WHERE user_id = ?")
                        ->execute([$advisor_course_id, $new_user_id]);
                } else {
                    db()->prepare("INSERT INTO course_advisors (user_id, course_id, assigned_by) VALUES (?, ?, ?)")
                        ->execute([$new_user_id, $advisor_course_id, current_user()['id']]);
                }
            } else {
                // Si no es asesor, eliminar cualquier registro previo
                db()->prepare("DELETE FROM course_advisors WHERE user_id = ?")->execute([$new_user_id]);
            }
        } else {
            // Si cambió el rol y ya no es docente, limpiar asesoría
            db()->prepare("DELETE FROM course_advisors WHERE user_id = ?")->execute([$new_user_id]);
        }

        audit($id > 0 ? 'update_user' : 'create_user', 'users', $new_user_id);
    } catch (Throwable $e) { 
        flash('error', 'Error (posible usuario duplicado): ' . $e->getMessage()); 
    }
    redirect('users.php');
}

/**
 * Elimina un usuario por su ID.
 * @param int $id ID del usuario a eliminar
 */
function users_delete($id) {
    require_perm('users');
    verify_csrf();
    
    $id = (int)$id;
    
    // Protección: no permitir eliminar al administrador principal (id=1)
    if ($id === 1) {
        flash('error', 'No se puede eliminar el usuario administrador principal.');
        redirect('users.php');
    }
    
    // Verificar que el usuario existe antes de eliminar
    $st = db()->prepare('SELECT username, role FROM users WHERE id = ?');
    $st->execute([$id]);
    $user = $st->fetch();
    
    if (!$user) {
        flash('error', 'El usuario no existe.');
        redirect('users.php');
    }
    
    try {
        db()->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
        flash('success', 'Usuario "' . e($user['username']) . '" eliminado correctamente.');
        audit('delete_user', 'users', $id);
    } catch (Throwable $e) {
        flash('error', 'Error al eliminar el usuario: ' . $e->getMessage());
    }
    redirect('users.php');
}