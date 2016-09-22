<?php

namespace App\Controllers;

use App\Models\Play;
use App\Repositories\Console\ConsoleCacheRepository as Cache;

class ConsoleController
{

    public function process($param)
    {
        $endingMessage = null;

        $repository = new Cache();
        $model = new Play($repository);

        if ($param === 'show') {
            return [
                'message' => 'Show',
                'result' => $model->getFleet()
            ];
        } elseif ($model->validation($param) == false) {
            return ['message' => 'error'];
        }

        $count = $model->getCountShoots();

        $result = $model->strike(ucfirst($param));

        $message = 'Miss';

        if (strlen($param) === 2 && $result[ucfirst($param)[0]][$param[1]] == 1) {
            $message = 'Hit';
        } elseif (strlen($param) === 3 && $result[ucfirst($param)[0]][$param[1].$param[2]]
            == 1) {
            $message = 'Hit';
        }

        if($model->checkGameStatus($result) === true){
            $endingMessage = "You finished the game with <b>$count</b> shoots.";
        }

        return [
            'message' => $message,
            'result' => $result,
            'endingMessage' => $endingMessage
        ];
    }
}
