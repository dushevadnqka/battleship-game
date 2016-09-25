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
     *
     * @todo check is need 
     */
    public function invalidate($param)
    {
        unset($_SESSION[$param]);
    }

    /**
     * fetch the result for certain item
     * @return WebCacheRepository one
     */
    public function find($param)
    {
        if (array_key_exists($param, $_SESSION)) {
            return $_SESSION[$param];
        }
        return null;
    }

    public function hitting($ship, $part)
    {
        if (array_key_exists($part, $this->find('fleet')[$ship])) {
            unset($_SESSION['fleet'][$ship][$part]);

            $status = 'Hit';

            if (empty($_SESSION['fleet'][$ship])) {
                unset($_SESSION['fleet'][$ship]);

                $status = 'Sunk';
            }

            if (empty($_SESSION['fleet'])) {

                $status = 'End';
            }

            return $status;
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

            unset($_SESSION['open']);
        } else {
            $new = $open;
        }

        $_SESSION['open'] = $new;

        return $new;
    }

    public function counter()
    {
        return count($_SESSION['open']);
    }
}
