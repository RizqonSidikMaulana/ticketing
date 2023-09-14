<?php
namespace App\Business\Service;
use App\Business\Service\Port\TicketPortService;
use App\Business\Domain\Ticket;

require __DIR__ . '/Port/TicketPortService.php';


class TicketService implements TicketPortService{
    private $ticketRepository;
    private $eventRepository;
    private $dbConn;
    private $pool;

    public function __construct($ticketRepository, $eventRepository, $dbConn, $pool = null)
    {
        $this->ticketRepository = $ticketRepository;
        $this->eventRepository = $eventRepository;
        $this->dbConn = $dbConn;
        $this->pool = $pool;
    }
    public function generateTicket(Ticket $ticket, int $totalTicket)
    {
        $eventId = $ticket->getEventId();
        $checkEvent = $this->eventRepository->getEvent($eventId, $this->dbConn);
        if (count($checkEvent) == 0) {
            return "event tidak ditemukan\n";
        }
        
        // $this->ticketRepository->generateTicket(1,2, $this->dbConn);
    }
    public function checkTicketStatus(int $eventId, string $ticketCode)
    {
        return true;
    }
    public function updateTicket(int $eventId, string $ticketCode, bool $status)
    {
        return true;
    }

    protected function getRandCode()
    {
        $char = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $len = 10;
        return substr(str_shuffle($char), 0, $len);
    }
}
