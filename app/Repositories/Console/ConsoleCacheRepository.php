<?php

namespace App\Repositories\Console;

use App\Repositories\CacheRepositoryInterface as Cache;

class ConsoleCacheRepository implements Cache
{
    /**
     * using one static variable to store data in RAM
     * @var
     */
    protected static $storage;

    public function create(array $param)
    {
        if (!is_null($param) && is_array($param)) {
            static::$storage = $param;
        }
        /**
         * @todo the exception!!!
         */
        //throw new \Exception("The input data with key: (".key($param).") is not in correct format or missing.The Repository can not Create the entity.");
    }

    /**
     * @todo not finished..
     * @param array $param
     * @throws \Exception
     */
    public function update(array $param)
    {
        if (!is_null($param) && is_array($param) && array_key_exists(
            key($param),
            static::$storage
        )) {
            static::$storage[key($param)] = $param[key($param)];
        }
        throw new \Exception("The input data with key: (".key($param).") is not in correct format or missing.The Repository can not Update the entity.");
    }

    public function invalidate($param)
    {
        unset(static::$storage[$param]);
    }

    /**
     * fetch the result for all
     * static::$storage dump purpose
     * @return array
     */
    public function all()
    {
        return static::$storage;
    }

    /**
     * fetch the result for certain item
     * @return ConsoleCacheRepository one
     */
    public function find($param)
    {
        if (array_key_exists($param, static::$storage)) {
            return static::$storage[$param];
        }
        return null;
    }

    public function register($open)
    {
        if (array_key_exists('open', static::$storage)) {
            $old = static::$storage['open'];

            if (in_array(key($open), array_keys($old))) {
                $new = array_replace_recursive($old, $open);
            } else {
                $new = array_merge($old, $open);
            }

            unset(static::$storage['open']);
        } else {
            $new = $open;
        }

        static::$storage['open'] = $new;

        return $new;
    }

    public function counter()
    {
        return count(static::$storage['result'], COUNT_RECURSIVE) - 1;
    }
}
