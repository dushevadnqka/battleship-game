<?php

namespace App\Models;

use App\Repositories\CacheRepositoryInterface as Repository;

class Game
{
    public $config;
    public $repository;
    protected static $table;
    protected static $fleet;

    public function __construct(Repository $repository, $config)
    {
        $this->repository = $repository;
        $this->config     = $config->game;
    }

    public function createTable()
    {
        /**
         * config
         * default: 10
         */
        $length = 10;

        if (isset($this->config)) {
            $length = $this->config['side_length'];
        }

        foreach (range('A', 'Z') as $k => $v) {
            if ($k + 1 <= $length) {
                for ($x = 1; $x <= $length; $x++) {
                    static::$table[$v][$v.$x] = 0;
                }
            }
        }
    }

    public function createFleet()
    {
        /*
         * defaults
         */
        $numberOfShips    = 3;
        $battleShipLength = 5;
        $destroyerLength  = 4;

        /**
         * config
         */
        if (isset($this->config)) {
            $battleShipLength = $this->config['battleship_length'];
            $destroyerLength  = $this->config['destroyer_length'];
            $numberOfShips    = $this->config['number_of_ships'];
        }

        for ($i = 1; $i <= $numberOfShips; $i++) {
            if ($i == 2) {
                static::$fleet[$i] = $this->createShip($battleShipLength);
            }else{
                static::$fleet[$i] = $this->createShip($destroyerLength);
            }
        }

        $this->checkFleetOverlapping();
    }

    /**
     * 1 horizontal [A[1], A[2], A[3], A[4]]
     * 2 vertical A[1], B[1], C[1], D[1]
     */
    protected function createShip($length)
    {
        $choice = rand(1, 2);

        if ($choice === 1) {
            return $this->makeHorizontal($length);
        } else {
            return $this->makeVertical($length);
        }
    }

    protected function makeHorizontal($length)
    {
        $ship = [];

        $letterRandom = array_rand(static::$table, 1);
        $firstRandom = rand(1, $this->config['side_length'] - $length - 1);
        $lastInRange = $firstRandom + $length - 1;

        for ($i = $firstRandom; $i <= $lastInRange; $i++) {
            $ship[$letterRandom.$i] = 1;
        }

        return $ship;
    }

    protected function makeVertical($length)
    {
        $ship = [];

        $letters = array_keys(static::$table);

        $firstLetterIndex  = rand(0,
        $this->config['side_length'] - 1 - $length - 1);
        $randRowPoint      = rand(1, $this->config['side_length']);
        $lastLetterInRange = $firstLetterIndex + $length - 1;

        for ($i = $firstLetterIndex; $i <= $lastLetterInRange; $i++) {
            $ship[$letters[$i].$randRowPoint] = 1;
        }

        return $ship;
    }

    protected function checkFleetOverlapping()
    {
        $data = [];
        foreach (static::$fleet as $v) {
            $data = array_merge($data, $v);
        }

        $allPartCount = $this->config['destroyer_length'] + $this->config['destroyer_length'] + $this->config['battleship_length'];

        if($allPartCount !== count($data)){
            $this->createFleet();
        }
    }

    public function finishInitalization()
    {
        if (is_array(static::$table) && !empty(static::$table) && is_array(static::$fleet)
            && !empty(static::$fleet)) {
            $this->repository->create(['table' => static::$table, 'fleet' => static::$fleet]);
        }
    }

    public function getTable()
    {
        return $this->repository->find('table');
    }
}
