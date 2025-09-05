<?php

use App\Core\App;

const BASE_PATH = __DIR__ . '/../';

require_once BASE_PATH . "vendor/autoload.php";

$app = new App();
$app->run();
