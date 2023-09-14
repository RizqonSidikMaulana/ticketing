<?php
namespace App\Business\Domain;

class Ticket {
    private int $id;
    private string $code;
    private int $eventId;
    private string $status;
    private string $createdAt;
    private string $updatedAt;

    public function __construct($id = 0, $code = 0, $eventId = 0, $status = false, $createdAt = "", $updatedAt = "")
    {
        $this->id = $id;
        $this->code = $code;
        $this->eventId = $eventId;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
    }

    public function getEventId()
    {
        return $this->eventId;
    }

    public function setEventId(int $eventId)
    {
        $this->eventId = $eventId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(string $status)
    {
        $this->status=$status;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt=$createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt=$updatedAt;
    }
    
}
