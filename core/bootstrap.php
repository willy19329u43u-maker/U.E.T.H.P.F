<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/../models/Database.php';
require_once __DIR__ . '/Helpers.php';
require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/I18n.php';
require_once __DIR__ . '/Mailer.php';
require_once __DIR__ . '/ViewHelpers.php';
spl_autoload_register(function ($class) { $f = dirname(__DIR__) . '/models/' . $class . '.php'; if (is_file($f)) require_once $f; });
init_language(); init_theme();
