<?php

namespace App\Infrastructure\Port;

interface EventPort { 
    public function getEvent(int $eventId, $dbConn);
}