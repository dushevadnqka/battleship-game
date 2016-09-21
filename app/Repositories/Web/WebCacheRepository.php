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
            $_SESSION
        )) {
            $_SESSION[key($param)] = $param[key($param)];
        }
        throw new \Exception("The input data with key: (".key($param).") is not in correct format or missing.The Repository can not Update the entity.");
    }

    public function invalidate($param)
    {
        unset($_SESSION[$param]);
    }

    /**
     * fetch the result for all
     * $_SESSION dump purpose
     * @return array
     */
    public function all()
    {
        return $_SESSION;
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

    public function register($open)
    {
        if (array_key_exists('open', $_SESSION)) {
            $old = $_SESSION['open'];

            if (in_array(key($open), array_keys($old))) {
                $new = array_replace_recursive($old, $open);
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
        return count($_SESSION['result'], COUNT_RECURSIVE) -1;
    }
}
