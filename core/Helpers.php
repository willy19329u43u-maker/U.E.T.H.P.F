<?php
function e($v) { return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8'); }
function redirect($url) { header('Location: ' . $url); exit; }
function current_url() { return $_SERVER['PHP_SELF'] ?? 'index.php'; }
function flash($type, $msg) { $_SESSION['flash'][] = ['type'=>$type, 'message'=>$msg]; }
function consume_flashes() { $f = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $f; }
function can($perm) { $u = current_user(); if (!$u) return false; $list = $GLOBALS['PERMISSIONS'][$u['role']] ?? []; return in_array('*', $list, true) || in_array($perm, $list, true); }
function restricted($action) { $u = current_user(); if (!$u) return true; return in_array($action, $GLOBALS['ROLE_RESTRICTIONS'][$u['role']] ?? [], true); }
function is_asesor() { $u = current_user(); return $u && ($u['role'] ?? '') === 'docente' && !empty($u['is_asesor']); }
function audit($action, $entity = null, $entity_id = null) { try { $u = current_user(); db()->prepare('INSERT INTO audit_logs (user_id, action, entity, entity_id, ip, created_at) VALUES (?,?,?,?,?,NOW())')->execute([$u['id'] ?? null, $action, $entity, $entity_id, $_SERVER['REMOTE_ADDR'] ?? '']); } catch (Throwable $e) {} }
