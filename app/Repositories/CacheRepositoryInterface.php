<?php

namespace App\Repositories;

interface CacheRepositoryInterface
{
    public function create(array $param);
    public function invalidation($key, $subKey = null, $param = null);
    public function find($param);
    public function register(array $param);
}
