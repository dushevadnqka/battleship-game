<?php

namespace App\Models;

use App\Repositories\CacheRepositoryInterface as Repository;

class Play
{
    protected $repository;

    public function __construct(Repository $repository)
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
        if (empty($param)) {
            return false;
        }

        $input = preg_replace('/[^a-zA-Z0-9]+/', '', trim($param));

        $input = ucfirst($input);

        if (array_key_exists($input[0], $this->getTable()) && array_key_exists($input,
                $this->getTable()[$input[0]])) {
            return true;
        }

        return false;
    }

    public function strike($param)
    {
        $status         = 'Miss';
        $storageSegment = 'fleet';

        $open[$param] = 0;

        foreach ($this->getFleet() as $ship => $v) {

            if (array_intersect_key($open, $v)) {

                $open[$param] = 1;

                if (array_key_exists($param, $this->getFleet()[$ship])) {

                    $this->repository->invalidation($storageSegment, $ship,
                        $param);

                    $status = 'Hit';

                    if (empty($this->getFleet()[$ship])) {

                        $this->repository->invalidation($storageSegment, $ship);

                        $status = 'Sunk';
                    }

                    if (empty($this->getFleet())) {

                        $status = 'End';
                    }
                }
            }
        }

        return [
            'data' => $this->repository->register($open),
            'status' => $status
        ];
    }

    public function getCountShoots()
    {
        return $this->repository->counter();
    }
}
