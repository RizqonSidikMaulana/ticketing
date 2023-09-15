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

    public function checkTicketStatus(Ticket $ticket, $dbConn){
        $id = $ticket->getId();
        $code = $ticket->getCode();

        $query = $dbConn->prepare(
            "SELECT id, ticket_code, event_id, status, created_at, updated_at
            FROM ticket
            where event_id = :id and ticket_code = :code"
        );

        $query->bindParam(':id', $id, $dbConn::PARAM_INT);
        $query->bindParam(':code', $code, $dbConn::PARAM_STR);
        $query->execute();

        $resTicket = $query->fetch($dbConn::FETCH_OBJ);
        if ($resTicket) {
            $ticket_object = new Ticket();
            $ticket_object->setId($resTicket->id);
            $ticket_object->setCode($resTicket->ticket_code);
            $ticket_object->setEventId($resTicket->event_id);
            $ticket_object->setStatus($resTicket->status);
            $ticket_object->setCreatedAt($resTicket->created_at ?? '');
            $ticket_object->setUpdatedAt($resTicket->updated_at ?? '');
            
            return $ticket_object->toArray();
        }

        return false;
    }

    public function updateTicket(Ticket $ticket, bool $status, $dbConn){
        $code = $ticket->getCode();
        $query = $dbConn->prepare(
            "UPDATE ticket
                SET status = :status,
                updated_at = :updated_at
            WHERE ticket_code = :code"
        );
        $query->bindValue(':code', $code, $dbConn::PARAM_STR);
        $query->bindValue(':status', $status ? 1 : 0, $dbConn::PARAM_INT);
        $query->bindValue(':updated_at', 'NOW()', $dbConn::PARAM_STR);
        $query->execute();

        return $query->rowCount() > 0 ? true : false;
    }
}