<?php
namespace App\Config;

require_once __DIR__ . '/../Controller/TicketController.php';
require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/../Config/Response/Response.php';

use App\Config\Config;
use App\Controller\TicketController;
use App\Config\Response\Response;

class Router
{
    public function router($router)
    {
        $router->map('GET', '/ticket/[i:id]/[a:code]',
            function($id, $code) {
                $req = [
                    'id' => $id,
                    'code' => $code,
                ];

            // Parsing dependency to Controller.
            $config = new Config;
            $db = $config->dbConnection();
            $param = $config->getParameter();

            $ticketController = new TicketController($db, $param);
            $result = $ticketController->checkTicket($req);

            $response = new Response();
            $response->response($result);
        }
    );
        $router->map('PUT', '/ticket/[i:id]/[a:code]',  function($id, $code) {
            $requestBody = file_get_contents('php://input');
            $req = json_decode($requestBody, true);
            
            // Parsing dependency to Controller.
            $config = new Config;
            $db = $config->dbConnection();
            $param = $config->getParameter();

            $req['id'] = $id;
            $req['code'] = $code;

            $ticketController = new TicketController($db, $param);
            $result = $ticketController->updateTicket($req);

            $response = new Response();
            $response->response($result);
            }
        );
        
        return $router;
    }
}