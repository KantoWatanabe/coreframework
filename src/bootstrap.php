<?php
use App\Core\Config;

// Composer
$composerfile = __DIR__ . '/../vendor/autoload.php';
if (is_readable($composerfile)) {
    require_once($composerfile);
}

// AutoLoad
spl_autoload_register(function ($class) {

    $prefix = 'App\\';

    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);

    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Regiser Config
$env = @$_SERVER['app_env'];
if (php_sapi_name() === 'cli') {
    foreach ($argv as $key => $value) {
        if (preg_match('/^--env=[a-zA-Z0-9]+$/', $value)) {
            $env = str_replace('--env=', '', $value);
            break;
        }
    }
}
Config::create($env);

// Common Settings
if (Config::get('app_debug') === true) {
    ini_set('display_errors', 1);
    error_reporting(-1);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
date_default_timezone_set('Asia/Tokyo');
mb_internal_encoding("UTF-8");