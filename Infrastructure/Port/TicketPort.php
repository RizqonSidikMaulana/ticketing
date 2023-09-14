<?php

namespace App\Infrastructure\Port;

interface TicketPort { 
    public function generateTicket(int $eventId, int $totalTicket, $dbConn);
    // public function checkTicketStatus(int $eventId, string $ticketCode);
    // public function updateTicket(int $eventId, string $ticketCode, bool $status);
}