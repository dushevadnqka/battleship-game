<?php

namespace App\System;

use App\Models\Game;

class App
{
    private static $instance = null;
    private $config;

    private function __construct($config)
    {
        $this->config = $config;
    }

    public function run()
    {
        session_start();
        $repository = new \App\Repositories\Web\WebCacheRepository();
        $game       = new Game($repository, $this->config);

        /*
         * reload the storage only when the session is expired
         */
        if (isset($_SESSION) && !array_key_exists('table', $_SESSION)) {
            $this->initGame($game);
        }
        
        try {
            $controller = FrontController::getInstance($this->config);
            $controller->fire();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function runConsoleEdition()
    {
        $repository = new \App\Repositories\Console\ConsoleCacheRepository();
        $game       = new Game($repository, $this->config);
        $this->initGame($game);

        try {
            $play = new \App\Models\Play($repository);
            $controller = new ConsoleFrontController($play); //don't need singleton
            $controller->start();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function initGame($game)
    {
        try {
            $game->createTable();
            $game->createFleet();
            $game->finishInitalization();
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    /**
     *
     * @return App
     */
    public static function getInstance($config)
    {
        if (self::$instance == null) {
            self::$instance = new App($config);
        }
        return self::$instance;
    }
}
