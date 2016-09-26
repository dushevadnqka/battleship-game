<?php

namespace App\Controllers;

use App\Models\Play;
use App\Repositories\Console\ConsoleCacheRepository as Repository;

class ConsoleController
{
    protected $model;

    public function __construct()
    {
        $repository = new Repository();
        $this->model = new Play($repository);
    }

    public function process($param)
    {
        $message = 'Error';
        $data = [];

        if ($param === 'show') {

            foreach ($this->model->getFleet() as $v) {
                $data = array_merge($data, $v);
            }

            $message = 'Show';
        } elseif ($this->model->validation($param) === true) {
            $result = $this->model->strike(ucfirst($param));

            $data = $result['data'];
            $message = $result['status'];
        }

        return [
            'data' => $data,
            'message' => $message
        ];
    }
}
