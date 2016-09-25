<?php

namespace App\Repositories;

interface CacheRepositoryInterface
{
    public function create(array $param);
    public function find($param);
    public function hitting($ship, $part);
    public function register(array $param);
}
