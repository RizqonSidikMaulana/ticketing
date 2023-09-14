<?php
namespace App\Business\Service;

require __DIR__ . '/Port/TicketPortService.php';
require __DIR__ . '/../../vendor/autoload.php';

use App\Business\Service\Port\TicketPortService;
use App\Business\Domain\Ticket;
use PhpAmqpLib\Message\AMQPMessage;

class TicketService implements TicketPortService{
    private $ticketRepository;
    private $eventRepository;
    private $dbConn;
    private $rabbitMq;
    private $param;

    public function __construct($ticketRepository, $eventRepository, $dbConn, $rabbitMq, $param)
    {
        $this->ticketRepository = $ticketRepository;
        $this->eventRepository = $eventRepository;
        $this->rabbitMq = $rabbitMq;
        $this->dbConn = $dbConn;
        $this->param = $param;
    }
    public function generateTicket(Ticket $ticket, int $totalTicket)
    {
        // Get Parameter.
        $params = $this->param;
        
        // Validate EventId.
        $eventId = $ticket->getEventId();
        $checkEvent = $this->eventRepository->getEvent($eventId, $this->dbConn);
        if (count($checkEvent) == 0) {
            return "event tidak ditemukan\n";
        }
        // Get connection rabbitmq.
        $channel = $this->rabbitMq['channel'];
        $connection = $this->rabbitMq['connection'];
        $queueName = $this->rabbitMq['queueName'];

        // Define number of workers.
        $numWorkers = $params['rabbitmq']['num_worker'];

        $this->assignTaskToWorker($totalTicket, $numWorkers, $queueName, $channel, $ticket);
        // Close connection RabbitMq.
        $channel->close();
        $connection->close();
        
        return "silahkan menunggu";
    }
    public function checkTicketStatus(int $eventId, string $ticketCode)
    {
        return true;
    }
    public function updateTicket(int $eventId, string $ticketCode, bool $status)
    {
        return true;
    }

    public function createTicket()
    {
        // Get connection rabbitmq.
        $channel = $this->rabbitMq['channel'];
        $connection = $this->rabbitMq['connection'];
        $queueName = $this->rabbitMq['queueName'];

        $channel->queue_declare($queueName, false, false, false, false);
        $callback = function ($message) {
            $ticket = new Ticket();
            $ticket->unserialize($message->body);
            $this->ticketRepository->createTicket($ticket, $this->dbConn);
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        };

        $channel->basic_consume($queueName, '', false, false, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
        }

        $channel->close();
        $connection->close();
    }

    protected function getRandCode()
    {
        $char = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $len = 7;
        $prefix = $this->param['prefix_ticket'];
        return $prefix . substr(str_shuffle($char), 0, $len);
    }

    protected function produceTicket($start, $end, $queueName, $channel, $ticket)
    {
        for ($i = $start; $i <= $end; $i++) {
            $code = $this->getRandCode();
            $ticket->setCode($code);

            $message = new AMQPMessage($ticket->serialize($ticket));
            $channel->basic_publish($message, '', $queueName);
        }
    }

    protected function assignTaskToWorker($totalTicket, $numWorkers, $queueName, $channel, $ticket)
    {
        // Divide ticket to workers.
        $group = ceil($totalTicket / $numWorkers);
        for ($i = 1; $i <= $numWorkers; $i++) {
            $start = ($i - 1) * $group + 1;
            $end = min($i * $group, $totalTicket);
            $this->produceTicket($start, $end, $queueName, $channel, $ticket);
        }
    }
}