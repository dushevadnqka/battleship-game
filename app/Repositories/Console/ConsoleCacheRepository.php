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
    protected static $shoots;

    public function create(array $param)
    {
        if (!is_null($param) && is_array($param)) {
            static::$storage = $param;
        }
    }

    /**
     * remove storage resources
     * @param type $key
     * @param type $subKey
     * @param type $param
     */
    public function invalidation($key, $subKey = null, $param = null)
    {
        if (isset($subKey) && isset($param) && array_key_exists($param,
                static::$storage[$key][$subKey])) {
            unset(static::$storage[$key][$subKey][$param]);
        } elseif (isset($subKey)) {
            unset(static::$storage[$key][$subKey]);
        } else {
            unset(static::$storage[$key]);
        }
    }

    /**
     * fetch the result for certain item
     * @return ConsoleCacheRepository
     */
    public function find($param)
    {
        if (isset(static::$storage) && array_key_exists($param, static::$storage)) {
            return static::$storage[$param];
        }
        return null;
    }

    public function register(array $open)
    {
        if (array_key_exists('open', static::$storage)) {
            $old = static::$storage['open'];

            if (in_array(key($open), array_keys($old))) {
                $new = $old;
            } else {
                $new = array_merge($old, $open);
            }

            $this->invalidation('open');
        } else {
            $new = $open;
        }

        static::$storage['open'] = $new;

        return $new;
    }
}
