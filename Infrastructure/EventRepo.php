<?php

namespace App\Infrastructure;

require __DIR__ . '/Port/EventPort.php';
require __DIR__ . '/../Business/Domain/Event.php';

use App\Infrastructure\Port\EventPort;
use App\Business\Domain\Event;

class EventRepo implements EventPort {
    public function getEvent(Event $event, $dbConn){
        $eventId = $event->getId();

        $query = $dbConn->prepare("SELECT id, event_name, created_at FROM event WHERE id = ?");
        $query->bindValue(1, $eventId, $dbConn::PARAM_INT);
        $query->execute();
        
        return $query->fetchAll($dbConn::FETCH_CLASS, 'App\\Business\\Domain\\Event');
    }
}