<?php

namespace App\Infrastructure\Port;

use App\Business\Domain\Ticket;

interface TicketPort { 
    public function createTicket(Ticket $ticket, $dbConn);
    // public function checkTicketStatus(int $eventId, string $ticketCode);
    // public function updateTicket(int $eventId, string $ticketCode, bool $status);
}