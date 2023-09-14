<?php

namespace App\Business\Controller;

require __DIR__ . './../vendor/autoload.php';
require __DIR__ . './../Business/Service/TicketService.php';
require __DIR__ . './../Business/Domain/Ticket.php';
require __DIR__ . './../Infrastructure/TicketRepo.php';
require __DIR__ . './../Infrastructure/EventRepo.php';
require __DIR__ . './../Config/Config.php';

use App\Business\Service\TicketService;
use App\Infrastructure\TicketRepo;
use App\Infrastructure\EventRepo;
use App\Config\Config;
use App\Business\Domain\Ticket;

if ($argc < 2) {
    echo "Usage: php cmd.php generate [event_id] [total_ticket]\n";
    exit(1);
}

$command = $argv[1];

if ($command === 'generate') {
    if ($argc == 4) {        
        // Validate character.
        if (!is_numeric($argv[2]) || !is_numeric($argv[3])) {
            echo "Please use number only for [event_id] and [total_ticket]\n";
            exit(1);
        }
        // Validate numbers.
        $id = intval($argv[2]);
        $total = intval($argv[3]);

        if ($id <= 0 || $total <= 0) {
            echo "Arguments must be greater than zero\n";
            exit(1);
        }
        // Get Configuration.
        $config = new Config();
        $db = $config->dbConnection();
        $param = $config->getParameter();
        $rabbitMq = $config->rabbitMQConnection('ticket');

        // Initiate repository and service.
        $ticketRepo = new TicketRepo();
        $eventRepo = new EventRepo();
        $port = new TicketService($ticketRepo, $eventRepo, $db, $rabbitMq, $param);

        // Object Ticket.
        $ticket = new Ticket();
        $ticket->setEventId($id);
        $ticket->setStatus(false);

        echo $port->generateTicket($ticket, $total) . "\n";
    }
} elseif ($command == 'listen') {
    // Get Configuration.
    $config = new Config();
    $db = $config->dbConnection();
    $param = $config->getParameter();
    $rabbitMq = $config->rabbitMQConnection('ticket');

    // Initiate repository and service.
    $ticketRepo = new TicketRepo();
    $eventRepo = new EventRepo();
    $port = new TicketService($ticketRepo, $eventRepo, $db, $rabbitMq, $param);

    $port->createTicket();
} elseif ($command == 'serve') {
    shell_exec('cd .. & php -S localhost:8000');
} else {
    echo "Unknown command: $command\n";
    exit(1);
}