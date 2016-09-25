<?php

namespace App\Controllers;

use App\Models\Play;
use App\System\View;
use App\Repositories\Web\WebCacheRepository as Repository;

class MainController
{
    protected $model;
    protected $view;

    public function __construct()
    {
        $this->view  = new View();
        $repository  = new Repository();
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
        unset($_SESSION['result']);

        $flash_message = 'Error';
        $location      = '/';

        if (isset($_POST) && array_key_exists('coordinates', $_POST)) {

            $input = ucfirst($_POST['coordinates']);

            if ($input == 'Show') {

                $flash_message = "Show";

                $result['data'] = [];

                foreach ($this->model->getFleet() as $v) {
                    $result['data'] = array_merge($result['data'], $v);
                }
            } elseif ($this->model->validation($input) === true) {

                $result = $this->model->strike($input);

                /**
                 * after status "End".. game over
                 */
                if ($result['status'] === "End") {
                    $location = "ending";
                }

                $flash_message = $result['status'];
            }
        }

        $_SESSION['flash_message'] = $flash_message;
        $_SESSION['result']        = $result['data'];

        header("Location:$location");
    }

    public function ending()
    {
        if (isset($_SESSION) && array_key_exists('fleet', $_SESSION) && empty($_SESSION['fleet'])) {
            return $this->view->setView(
                    'web-ending',
                    [
                    'count' => $this->model->getCountShoots()
                    ]
            );
        }
        header("Location:/");
    }

    public function regame()
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $uri = (explode('/',
                    str_replace('http://', '', $_SERVER['HTTP_REFERER'])));

            if (end($uri) === "ending") {
                session_destroy();
            }
        }
        header("Location:/");
    }
}
