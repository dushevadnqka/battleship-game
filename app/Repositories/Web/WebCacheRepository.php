<?php

namespace App\Repositories\Web;

use App\Repositories\CacheRepositoryInterface as Cache;

class WebCacheRepository implements Cache
{

    public function create(array $param)
    {
        if (!is_null($param) && is_array($param)) {
            $_SESSION = $param;
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
                $_SESSION[$key][$subKey])) {
            unset($_SESSION[$key][$subKey][$param]);
        } elseif (isset($subKey)) {
            unset($_SESSION[$key][$subKey]);
        } else {
            unset($_SESSION[$key]);
        }
    }

    /**
     * fetch the result for certain item
     * @return WebCacheRepository
     */
    public function find($param)
    {
        if (array_key_exists($param, $_SESSION)) {
            return $_SESSION[$param];
        }
    }

    public function register(array $open)
    {
        if (array_key_exists('open', $_SESSION)) {

            $old = $_SESSION['open'];

            if (in_array(key($open), array_keys($old))) {
                $new = $old;
            } else {
                $new = array_merge($old, $open);
            }

            $this->invalidation('open');
        } else {
            $new = $open;
        }

        $_SESSION['open'] = $new;

        return $new;
    }
}
