<?php
require_once __DIR__ . '/../core/bootstrap.php';
function profile_page() {
    require_login();
    $u = current_user();
    return ['user' => $u];
}
