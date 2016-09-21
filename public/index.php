<?php
ini_set('display_errors', 1);

$app = require __DIR__.'/../bootstrap/app.php';

if (php_sapi_name() === 'cli') {
    $app->runConsoleEdition();
} else {
    $app->run();
}
