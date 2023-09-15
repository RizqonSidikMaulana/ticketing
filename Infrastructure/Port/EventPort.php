<?php

namespace App\Infrastructure\Port;

use App\Business\Domain\Event;

interface EventPort {
    public function getEvent(Event $event, $dbConn);
}