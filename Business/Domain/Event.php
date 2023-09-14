<?php
namespace App\Business\Domain;

class Event {
    private int $id;
    private string $eventName;
    private string $createdAt;
    private string $updatedAt;

    public function __construct($id = 0, $eventName = "", $createdAt = "", $updatedAt = "")
    {
        $this->id = $id;
        $this->eventName = $eventName;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getEventName()
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName)
    {
        $this->eventName = $eventName;
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
