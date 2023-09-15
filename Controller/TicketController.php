<?php

namespace App\Controller;

require_once __DIR__ . '/../Business/Service/TicketService.php';
require_once __DIR__ . './../Infrastructure/TicketRepo.php';
require_once __DIR__ . './../Infrastructure/EventRepo.php';
require_once __DIR__ . '/../Business/Domain/Ticket.php';

use App\Business\Domain\Ticket;
use App\Business\Service\TicketService;
use App\Infrastructure\TicketRepo;
use App\Infrastructure\EventRepo;

class TicketController {
    private $db;
    private $param;

    public function __construct($db, $param)
    {
        $this->db = $db;
        $this->param = $param;
    }

    public function checkTicket($req) {
        $id = $req['id'];
        $code = $req['code'];
        
        if (!is_numeric($req['id']) || strlen($req['code']) != 10) {
            return [
                'status' => false,
                'message' => 'wrong event id or ticket code',
                'data' => []
            ];
        }

        $ticket = new Ticket();
        $ticket->setId($id);
        $ticket->setCode($code);

        $ticketRepo = new TicketRepo();
        $eventRepo = new EventRepo();
        $db = $this->db;
        $param = $this->param;

        $ticketService = new TicketService($ticketRepo, $eventRepo, $db, $param);

        $result = $ticketService->checkTicketStatus($ticket);
        if (!$result['status']) {
            return $result;
        }

        $data['ticket_code'] = $result['data']['ticket_code'];
        $data['status'] = $result['data']['status'];
        $result['data'] = $data;
        
        return $result;
        
    }

    public function updateTicket($req)
    {
        $id = $req['id'];
        $code = $req['code'];

        if (!key_exists('status', $req)) {
            return [
                'status' => false,
                'message' => 'status invalid',
                'data' => []
            ];
        }

        $status = $req['status'];

        if (!is_numeric($req['id']) || strlen($req['code']) != 10 || !is_bool($status)) {
            return [
                'status' => false,
                'message' => 'wrong event id, ticket code or status',
                'data' => []
            ];
        }

        $ticketRepo = new TicketRepo();
        $eventRepo = new EventRepo();
        $db = $this->db;
        $param = $this->param;

        $ticket = new Ticket();
        $ticket->setId($id);
        $ticket->setCode($code);

        $ticketService = new TicketService($ticketRepo, $eventRepo, $db, $param);
        $result = $ticketService->checkTicketStatus($ticket);
        if (!$result['status']) {
            return $result;
        }

        $result = $ticketService->updateTicket($ticket, $status);
        if (!$result['status']) {
            return $result;
        }

        $data['ticket_code'] = $result['data']['ticket_code'];
        $data['status'] = $result['data']['status'];
        $data['updated_at'] = $result['data']['updated_at'];
        $result['data'] = $data;
        
        return $result;
    }
}
