<?php
define('DB_HOST', '127.0.0.1'); define('DB_NAME', 'ue_paulo_freire'); define('DB_USER', 'root'); define('DB_PASS', '');
define('APP_NAME', 'U.E. Paulo Freire'); define('APP_URL', 'http://localhost/ue_paulo_freire_web'); define('APP_VERSION', '5.0');
define('MAIL_ENABLED', false); define('MAIL_HOST', 'smtp.gmail.com'); define('MAIL_PORT', 587); define('MAIL_ENCRYPTION', 'tls'); define('MAIL_USER', ''); define('MAIL_PASS', ''); define('MAIL_FROM', 'no-responder@uepaulofreire.edu.bo'); define('MAIL_FROM_NAME', 'U.E. Paulo Freire');

$GLOBALS['PERMISSIONS'] = [
    'admin' => ['*'], 
    'secretaria' => ['students','attendance','tardies','schedules','calendar','announcements','grades','reports','users','audit','notifications','profile'], 
    'docente' => ['attendance','tardies','grades','schedules','calendar','reports','notifications','profile','announcements_area'], 
    'padre' => ['children','notifications','profile','comunicados','schedules','calendar'], 
    'estudiante' => ['own','notifications','profile','comunicados','schedules','calendar']];

$GLOBALS['ROLE_PALETTE'] = [
    'admin' => 'palette-metal', 
    'secretaria' => 'palette-metal', // Usará la misma base que el admin pero puedes crear 'palette-slate' si prefieres
    'docente' => 'palette-aurora',   // NUEVO
    'estudiante' => 'palette-glacier', 
    'padre' => 'palette-mist'        // NUEVO
];

$GLOBALS['ROLE_RESTRICTIONS'] = [
    'secretaria' => ['backup', 'settings', 'delete_users_admin'], 
    'docente' => ['backup', 'settings', 'users', 'audit', 'students.edit', 'schedules.edit'], 
    'padre' => ['backup', 'settings', 'users', 'audit', 'edit_any'], 
    'estudiante' => ['backup', 'settings', 'users', 'audit', 'edit_any']];
