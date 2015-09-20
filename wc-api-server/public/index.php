<?php
define('REQUEST_MICROTIME', microtime(true));
define('INDEX_DIR', dirname(__FILE__));
date_default_timezone_set('Europe/Kiev');

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

$appConfig = include APPLICATION_PATH . '/config/application.config.php';

if (file_exists(APPLICATION_PATH . '/config/development.config.php')) {
    $appConfig = Zend\Stdlib\ArrayUtils::merge($appConfig, include APPLICATION_PATH . '/config/development.config.php' );
}

// Run the application!
Zend\Mvc\Application::init($appConfig)->run();
