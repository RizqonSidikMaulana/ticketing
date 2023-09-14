<?php

namespace App\Infrastructure;

require __DIR__ . '/Port/TicketPort.php';

use App\Infrastructure\Port\TicketPort;
use App\Business\Domain\Ticket;

class TicketRepo implements TicketPort {
    public function createTicket(Ticket $ticket, $dbConn){
        $query = $dbConn->prepare("INSERT INTO ticket (event_id, ticket_code, status, created_at) VALUES (?, ?, ?, ?)");
        $query->bindValue(1, $ticket->getEventId(), $dbConn::PARAM_INT);
        $query->bindValue(2, $ticket->getCode(), $dbConn::PARAM_STR);
        $query->bindValue(3, $ticket->getStatus() ? 1 : 0, $dbConn::PARAM_INT);
        $query->bindValue(4, 'NOW()', $dbConn::PARAM_STR);
        $query->execute();
        
        return true;
    }
}