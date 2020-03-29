<?php
require_once(__DIR__ . '/../src/bootstrap.php');

$app = new App\Core\Application();
$app->runCmd($argv);
