<?php

namespace App\Business\Controller;
use App\Business\Service\TicketService;
use App\Infrastructure\TicketRepo;
use App\Infrastructure\EventRepo;
use App\Config\Config;
use Spatie\Async\Pool;
use App\Business\Domain\Ticket;

require __DIR__ . './../vendor/autoload.php';
require __DIR__ . './../Business/Service/TicketService.php';
require __DIR__ . './../Business/Domain/Ticket.php';
require __DIR__ . './../Infrastructure/TicketRepo.php';
require __DIR__ . './../Infrastructure/EventRepo.php';
require __DIR__ . './../Config/Config.php';

if ($argc < 2) {
    echo "Usage: php cmd.php generate [event_id] [total_ticket]\n";
    exit(1);
}

$command = $argv[1];

if ($command === 'generate') {
    if ($argc == 4) {        
        // validate character.
        if (!is_numeric($argv[2]) || !is_numeric($argv[3])) {
            echo "Please use number only for [event_id] and [total_ticket]\n";
            exit(1);
        }

        // validate numbers.
        $id = intval($argv[2]);
        $total = intval($argv[3]);

        if ($id <= 0 || $total <= 0) {
            echo "Arguments must be greater than zero\n";
            exit(1);
        }

        // Create Pool.
        $pool = Pool::create();

        // Initiate Database.
        $config = new Config();
        $db = $config->dbConnection();

        // Initiate repository and service.
        $ticketRepo = new TicketRepo();
        $eventRepo = new EventRepo();
        $port = new TicketService($ticketRepo, $eventRepo, $db, $pool);

        // Object Ticket.
        $ticket = new Ticket();
        $ticket->setEventId($id);

        echo $port->generateTicket($ticket, $total);
    }
} elseif ($command == 'serve') {
    shell_exec('cd .. & php -S localhost:8000');
} 
else {
    echo "Unknown command: $command\n";
    exit(1);
}