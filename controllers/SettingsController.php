<?php
require_once __DIR__ . '/../core/bootstrap.php';
function settings_page() { require_login(); if (restricted('settings')) { http_response_code(403); exit('Acceso denegado.'); } $sm = new SettingsModel(); return ['app_name' => $sm->get('app_name', APP_NAME), 'hero_text' => $sm->get('hero_text', ''), 'mail_enabled' => $sm->get('mail_enabled', '0')]; }
function settings_save() { require_login(); if (restricted('settings')) { http_response_code(403); exit('Acceso denegado.'); } verify_csrf(); $sm = new SettingsModel(); $sm->set('app_name', trim($_POST['app_name'] ?? APP_NAME)); $sm->set('hero_text', trim($_POST['hero_text'] ?? '')); $sm->set('mail_enabled', $_POST['mail_enabled'] ?? '0'); flash('success', 'Configuración guardada.'); audit('update_settings'); redirect('settings.php'); }
