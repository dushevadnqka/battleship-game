<?php

namespace App\Models;

use App\Repositories\CacheRepositoryInterface as Repository;

class Game
{
    public $config;
    public $repository;
    protected static $table;
    protected static $fleet;
    protected static $boat;

    public function __construct(Repository $repository, $config)
    {
        $this->repository = $repository;
        $this->config     = $config->game;
    }

    public function createTable()
    {
        $data = [];

        /**
         * @config
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
        $battleShipLength = 5;
        $destroyerLength  = 4;

        /**
         * @config
         */
        if (isset($this->config)) {
            $battleShipLength = $this->config['battleship_length'];
            $destroyerLength  = $this->config['destroyer_length'];
        }

        for ($i = 0; $i <= 2; $i++) {
            if ($i == 1) {
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
        (int) $firstRandom  = rand(1, 10 - intval($length - 1));
        (int) $lastInRange  = $firstRandom + intval($length - 1);

        /*
         * it is very important to check whlole chunk values simultaneously it could be array_slice
         */
        for ($i = $firstRandom; $i <= $lastInRange; $i++) {
            $chunk[$letterRandom][$i] = $row[$i];
            $affected[]               = $i;
        }

        if (array_sum($chunk) === 0) {
            foreach ($affected as $v) {
                static::$fleet[$letterRandom][$v] = 1;
            }
        }
    }

    /**
     * @todo config
     * @todo th whole method- overlapping boats
     */
    public function makeVertical($length)
    {
        $chunk = [];

        $letters = array_keys(static::$table);

        (int) $firstLetterIndex  = rand(0, 9 - intval($length - 1));
        (int) $randRowPoint      = rand(1, 10);
        (int) $lastLetterInRange = $firstLetterIndex + intval($length - 1);

        $letter = $letters[$firstLetterIndex];

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
