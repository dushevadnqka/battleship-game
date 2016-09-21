<?php

namespace App\Repositories;

interface CacheRepositoryInterface
{
    public function create(array $param);
    public function update(array $param);
    public function all();
    public function find($param);
}
