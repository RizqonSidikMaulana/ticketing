<?php

namespace App\Business\Service\Port;
use App\Business\Domain\Ticket;

interface TicketPortService {
    public function generateTicket(Ticket $ticket, int $totalTicket);
    public function checkTicketStatus(Ticket $ticket);
    public function updateTicket(Ticket $ticket, $status);
    public function createTicket();
}