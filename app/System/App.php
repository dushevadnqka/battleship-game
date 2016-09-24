<?php

namespace App\System;

use App\Models\Game;

class App
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function runWebEdition()
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
            $controller = new WebFrontController($this->config);
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
            $controller = new ConsoleFrontController($play);
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
}
