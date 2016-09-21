<?php

namespace App\System;

use App\System\View;
use App\Models\Play;

class ConsoleFrontController
{
    private $model;
    private $view;

    public function __construct(Play $model)
    {
        $this->model = $model;
        $this->view = new View();
    }

    public function start()
    {
        return $this->view->setView(
            'console-draw',
            [
                'table' => $this->model->getTable(),
                ]
        );
    }
}
