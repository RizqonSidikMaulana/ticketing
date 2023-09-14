<?php

namespace App\Business\Service\Port;
use App\Business\Domain\Ticket;

interface TicketPortService {
    public function generateTicket(Ticket $eventId, int $totalTicket);
    public function checkTicketStatus(int $eventId, string $ticketCode);
    public function updateTicket(int $eventId, string $ticketCode, bool $status);
}