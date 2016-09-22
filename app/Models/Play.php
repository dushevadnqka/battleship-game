<?php

namespace App\Models;

use App\Repositories\CacheRepositoryInterface as Cache;

class Play
{
    protected $repository;

    public function __construct(Cache $repository)
    {
        $this->repository = $repository;
    }

    public function getTable()
    {
        return $this->repository->find('table');
    }

    public function getFleet()
    {
        return $this->repository->find('fleet');
    }

    public function validation($param)
    {
        $input = preg_replace("/[^a-zA-Z0-9]+/", "", trim($param));

        $input = ucfirst($input);

        /*
         * the number "10" case (3 chars)
         */
        if (strlen($input) === 3 && array_key_exists(
            $input[0],
            $this->getTable()
        ) && array_key_exists(
            $input[1].$input[2],
            $this->getTable()[$input[0]]
        )) {
            return true;
        }

        if (strlen($input) === 2 && array_key_exists(
            $input[0],
            $this->getTable()
        ) && array_key_exists(
            $input[1],
            $this->getTable()[$input[0]]
        )) {
            return true;
        }

        return false;
    }

    public function strike($param)
    {
        $open = [];

        if (strlen($param) === 2) {
            $open[$param[0]][$param[1]] = 0;

            if (isset($this->getFleet()[$param[0]][$param[1]])) {
                $open[$param[0]][$param[1]] = 1;
            }
        } elseif (strlen($param) === 3) {
            $open[$param[0]][$param[1].$param[2]] = 0;

            if (isset($this->getFleet()[$param[0]][$param[1].$param[2]])) {
                $open[$param[0]][$param[1].$param[2]] = 1;
            }
        }

        $result = $this->registerShoot($open);

        return $result;
    }

    public function registerShoot($open)
    {
        return $this->repository->register($open);
    }

    public function checkGameStatus($open)
    {
        $check = array_intersect_key($open, $this->getFleet());

        /**
         * @todo config
         */
        if (count($check) === 3 && count($check) === count($this->getFleet())) {
            return true;
        }

        return false;
    }

    public function getCountShoots()
    {
        return $this->repository->counter();
    }
}
