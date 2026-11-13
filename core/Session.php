<?php
function current_user() { return $_SESSION['user'] ?? null; }
function is_logged() { return current_user() !== null; }
function require_login() { if (!is_logged()) redirect('login.php'); }
function require_perm($perm) { require_login(); if (!can($perm)) { http_response_code(403); exit('Acceso denegado para su rol.'); } }
function init_language() { if (isset($_GET['lang']) && in_array($_GET['lang'], ['es','qu'], true)) $_SESSION['lang'] = $_GET['lang']; if (empty($_SESSION['lang'])) $_SESSION['lang'] = current_user()['language'] ?? 'es'; }
function init_theme() { if (isset($_GET['theme']) && in_array($_GET['theme'], ['dark','light'], true)) $_SESSION['theme'] = $_GET['theme']; if (empty($_SESSION['theme'])) $_SESSION['theme'] = current_user()['theme'] ?? 'dark'; }
function role_palette() { $u = current_user(); return $GLOBALS['ROLE_PALETTE'][$u['role'] ?? 'admin'] ?? 'palette-metal'; }
function authenticate_user($username, $password) { if (login_rate_limited($username)) return null; try { $st = db()->prepare('SELECT * FROM users WHERE username = ? AND active = 1 LIMIT 1'); $st->execute([$username]); $u = $st->fetch(); if ($u && verify_password($password, $u['password_hash'])) { register_login_attempt($username, true); return $u; } register_login_attempt($username, false); return null; } catch (Throwable $e) { return null; } }
