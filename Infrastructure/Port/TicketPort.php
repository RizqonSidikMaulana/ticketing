<?php

namespace App\Infrastructure\Port;

use App\Business\Domain\Ticket;

interface TicketPort {
    public function createTicket(Ticket $ticket, $dbConn);
    public function checkTicketStatus(Ticket $ticket, $dbConn);
    public function updateTicket(Ticket $ticket, bool $status, $dbConn);
}
