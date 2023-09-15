<?php
namespace App\Config;

require __DIR__ . '/../Config/Router.php';

use App\Config\Router;

class Application
{
    public function run($router): void
    {
        $routing = new Router;
        $routing->router($router);
        $match = $router->match();

        if( is_array($match) && is_callable( $match['target'] ) ) {
            call_user_func_array($match['target'], $match['params']);
        } else {
            header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        }
    }

    public function getParam()
    {
        $request = [];
        if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            parse_str(file_get_contents("php://input"), $request);
        }
    }
}