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

    public function hitting($ship, $part)
    {
        if (array_key_exists($part, $this->find('fleet')[$ship])) {
            unset(static::$storage['fleet'][$ship][$part]);

            $status = 'Hit';

            if (empty(static::$storage['fleet'][$ship])) {
                unset(static::$storage['fleet'][$ship]);

                $status = 'Sunk';
            }

            if (empty(static::$storage['fleet'])) {

                $status = 'End';
            }

            return $status;
        }
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

            unset(static::$storage['open']);
        } else {
            $new = $open;
        }

        static::$storage['open'] = $new;

        return $new;
    }
}
