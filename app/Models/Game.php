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
                    static::$table[$v][$x] = 0;
                }
            }
        }
    }

    public function createFleet()
    {
        /*
         * defaults
         */
        $numberOfShips = 3;
        $battleShipLength = 5;
        $destroyerLength  = 4;

        /**
         * config
         */
        if (isset($this->config)) {
            $battleShipLength = $this->config['battleship_length'];
            $destroyerLength  = $this->config['destroyer_length'];
            $numberOfShips = $this->config['number_of_ships'];
        }

        for ($i = 1; $i <= $numberOfShips; $i++) {
            if ($i == 2) {
                $this->createShip($battleShipLength);
            } else {
                $this->createShip($destroyerLength);
            }
        }
    }

    /**
     * 1 horizontal A[1], A[2], A[3], A[4]
     * 2 vertical A[1], B[1], C[1], D[1]
     */
    public function createShip($length)
    {
        $choice = 1;//rand(1, 2);

        if ($choice === 1) {
            $this->makeHorizontal($length);
        } else {
            $this->makeVertical($length);
        }
    }

    /**
     * @todo config
     */
    public function makeHorizontal($length)
    {
        $chunk    = [];
        $affected = [];

        $letterRandom = array_rand(static::$table, 1);
        $row          = static::$table[$letterRandom];

        /**
         * @todo 10 is hardcoded!
         */
        $firstRandom  = rand(1, $this->config['side_length'] - $length - 1);
        $lastInRange  = $firstRandom + $length - 1;

        /*
         * it is very important to check whlole chunk values simultaneously it could be array_slice
         */
        for ($i = $firstRandom; $i <= $lastInRange; $i++) {
            $chunk[$letterRandom][$i] = $row[$i];
            $affected[]               = $i;
        }

        /*
         * array_diff with ready ships
         */
        if (array_sum($chunk) === 0) {
            foreach ($affected as $v) {
                static::$fleet[$letterRandom][$v] = 1;
            }
        }
    }

    /**
     * @todo config
     */
    public function makeVertical($length)
    {
        $chunk = [];

        $letters = array_keys(static::$table);

        $firstLetterIndex  = rand(0, $this->config['side_length'] -1 - $length - 1);
        $randRowPoint      = rand(1, $this->config['side_length']);
        $lastLetterInRange = $firstLetterIndex + $length - 1;

        /*
         * it is very important to check whlole chunk values simultaneously it could be array_slice
         */
        for ($i = $firstLetterIndex; $i <= $lastLetterInRange; $i++) {
            $chunk[$letters[$i]][$randRowPoint] = static::$table[$letters[$i]][$randRowPoint];
        }

        if (array_sum($chunk) === 0) {
            foreach ($chunk as $k => $v) {
                static::$fleet[$k][key($v)] = 1;
            }
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
