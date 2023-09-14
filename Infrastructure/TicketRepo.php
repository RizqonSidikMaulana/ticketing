<?php

namespace App\Infrastructure;

use App\Infrastructure\Port\TicketPort;

require __DIR__ . '/Port/TicketPort.php';

class TicketRepo implements TicketPort {
    public function generateTicket(int $eventId, int $totalTicket, $dbConn){
        echo "hore";
    }
}