<?php if (!defined('APP_NAME')) exit;

$u = current_user(); 
$lang = $_SESSION['lang'] ?? 'es'; 
$theme = $_SESSION['theme'] ?? 'dark'; 
$palette = role_palette(); 
$flashes = consume_flashes(); 
$sm = new SettingsModel(); 
$appName = $sm->get('app_name', APP_NAME); 
$avatar = ($u['avatar'] ?? '') ?: 'assets/img/avatars/avatar-' . ($u['role'] ?? 'estudiante') . '.svg'; 
$name = ($u['full_name'] ?? '') ?: ($u['username'] ?? 'Usuario');

$menu = [
    ['dashboard.php', t('dashboard'), '🏠']
];
if (can('students')) $menu[] = ['students.php', t('students'), '🎓'];
if (can('attendance')) $menu[] = ['attendance.php', t('attendance'), '✅'];
if (can('tardies')) $menu[] = ['tardies.php', t('tardies'), '⏰'];
if (can('schedules')) $menu[] = ['schedules.php', t('schedules'), '🕒'];
if (can('calendar')) $menu[] = ['calendar.php', t('calendar'), '📅'];
if (can('announcements')) $menu[] = ['announcements.php', t('announcements'), '📢'];
if (can('grades')) $menu[] = ['grades.php', t('grades'), '📚'];
if (can('comunicados') || can('announcements')) $menu[] = ['comunicados.php', t('comunicados'), '📬'];
if (can('reports')) $menu[] = ['reports.php', t('reports'), '📊'];
if (can('users')) $menu[] = ['users.php', t('users'), '👥'];
if (can('audit')) $menu[] = ['audit.php', t('audit'), '🧾'];
if (can('*') && !restricted('settings')) $menu[] = ['settings.php', t('settings'), '⚙️'];
$menu[] = ['notifications.php', t('notifications'), '🔔'];
$menu[] = ['profile.php', t('profile'), '👤'];
?>
<!doctype html>
<html lang="<?= e($lang) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title ?? APP_NAME) ?> | <?= e($appName) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/app.css">
    <style>
        .search-form { flex: 1; max-width: 340px; }
    </style>
</head>
<body class="app-body theme-<?= e($theme) ?> <?= e(role_palette()) ?>">
<div class="layout">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <img src="assets/img/logo-escudo.svg" alt="Logo U.E. Paulo Freire" class="brand-logo">
            <span class="brand-text metal-text label"><?= e($appName) ?></span>
        </div>
        <nav class="menu">
            <?php foreach ($menu as $item): 
                $active = basename($_SERVER['PHP_SELF']) === basename($item[0]) ? 'active' : ''; 
            ?>
                <a class="menu-link <?= $active ?>" href="<?= e($item[0]) ?>" title="<?= e($item[1]) ?>">
                    <span class="ico"><?= $item[2] ?></span>
                    <span class="label"><?= e($item[1]) ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
    </aside>
    
    <div class="main">
        <header class="topbar">
            <div class="topbar-controls">
                <button id="menuToggle" class="btn icon-btn" aria-label="Menu">☰</button>
                <button id="sideToggle" class="btn icon-btn" aria-label="Expandir">⇄</button>
            </div>

            <h1 class="metal-text" style="margin-right: 15px;"><?= e($page_title ?? APP_NAME) ?></h1>
            <form class="search-form" method="get" action="search.php" style="margin-right: auto;">
                <input class="input" type="text" name="q" placeholder="🔍 Buscar..." value="<?= e($_GET['q'] ?? '') ?>">
            </form>

            <div class="topbar-user" style="display: flex; gap: 8px; align-items: center;">
                <a class="btn icon-btn" href="?theme=<?= $theme === 'dark' ? 'light' : 'dark' ?>" title="Cambiar Tema">
                    <?= $theme === 'dark' ? '☀️' : '🌙' ?>
                </a>
                
                <a class="btn icon-btn" href="?lang=<?= $lang === 'es' ? 'qu' : 'es' ?>" title="Cambiar Idioma">
                    <?= $lang === 'es' ? 'QU' : 'ES' ?>
                </a>

                <?php if ($u): ?>
                    <img src="<?= e($avatar) ?>" alt="Avatar" class="topbar-avatar" onerror="this.src='assets/img/avatars/avatar-estudiante.svg'">
                    <span class="badge badge-accent"><?= e($u['role']) ?></span>
                    
                    <a class="btn btn-danger small" href="logout.php" style="text-decoration: none;">
                        <?= e(t('logout')) ?>
                    </a>
                <?php endif; ?>
            </div>
        </header>

        <main class="content">
            <?php foreach ($flashes as $f): ?>
                <div class="alert alert-<?= e($f['type']) ?>"><?= e($f['message']) ?></div>
            <?php endforeach; ?>