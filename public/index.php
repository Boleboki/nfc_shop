<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use App\Core\App;

const BASE_PATH = __DIR__ . '/../';

require_once BASE_PATH . "vendor/autoload.php";

$app = new App();
$app->run();
