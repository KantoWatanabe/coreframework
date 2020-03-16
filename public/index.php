<?php
require_once(__DIR__ . '/../src/bootstrap.php');

$app = new App\Core\Application();
if (php_sapi_name() === 'cli') {
    $app->runCmd($argv);
} else {
    $app->run();
}