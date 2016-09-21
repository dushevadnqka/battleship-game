<?php
require_once __DIR__.'/../vendor/autoload.php';

/*
 * Simple DI only for config purposes
 */
$configInstance = new App\System\Config();
if ($configInstance->getConfigFolder() == null) {

    /**
     * check if prompting via cli (terminal)
     */
    if (php_sapi_name() === 'cli') {
        $configInstance->setConfigFolder(getcwd().'/config');
    } else {
        $configInstance->setConfigFolder(getcwd().'/../config');
    }
}

$app = \App\System\App::getInstance($configInstance);

return $app;
