<?php

namespace App\Controllers;

use App\Models\Play;
use App\System\View;
use App\Repositories\Web\WebCacheRepository as Cache;

class MainController
{
    protected $model;
    protected $view;

    public function __construct()
    {
        $this->view  = new View();
        $repository =  new Cache();
        $this->model = new Play($repository);
    }

    public function index()
    {
        return $this->view->setView(
            'web-draw',
            [
                'table' => $this->model->getTable()
                ]
        );
    }

    public function shoot()
    {
        /**
         * @todo invalidate method
         */
        unset($_SESSION['result']);
        
        if (isset($_POST) && array_key_exists('coordinates', $_POST)) {
            if ($_POST['coordinates'] == 'show') {
                $flash_message = 'Show';
                $result        = $this->model->getFleet();
            } elseif ($this->model->validation($_POST['coordinates']) == true) {
                $result = $this->model->strike(ucfirst($_POST['coordinates']));

                $flash_message = 'Miss';

                if (strlen($_POST['coordinates']) === 2 && $result[ucfirst($_POST['coordinates'])[0]][$_POST['coordinates'][1]]
                    == 1) {
                    $flash_message = 'Hit';
                } elseif (strlen($_POST['coordinates']) === 3 && $result[ucfirst($_POST['coordinates'])[0]][$_POST['coordinates'][1].$_POST['coordinates'][2]]
                    == 1) {
                    $flash_message = 'Hit';
                }
            } else {
                $flash_message = 'Error';
            }

            /**
             * finishes the game:
             * 1. count count of all targets
             * 2. count shoots
             * 3. set $_SESSION['finish_message']
             */
            if ($this->model->checkGameStatus()== true) {
                $count = $this->model->getCountShoots();

                $_SESSION['finish_message'] = "You finished the game with <b>$count</b>.";
            }

            /*
             * invalidate cache
             * and than catch new one
             */
            $_SESSION['flash_message'] = $flash_message;
            $_SESSION['result']        = $result;
            header("Location:/");
        }
    }
}
