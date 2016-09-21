<?php

namespace App\System;

class View
{

    public function setView($path, $params = null)
    {
        if (!$path) {
            throw new \Exception("No view path specified");
        }

        if (isset($params) && is_array($params)) {
            extract($params);
        }

        require __DIR__.'/../Views/'.$path.'.php';
    }
}
