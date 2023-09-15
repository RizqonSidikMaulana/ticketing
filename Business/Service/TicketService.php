<?php
namespace App\Business\Service;

require_once __DIR__ . '/Port/TicketPortService.php';
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Business\Service\Port\TicketPortService;
use App\Business\Domain\Ticket;
use App\Business\Domain\Event;
use PhpAmqpLib\Message\AMQPMessage;

class TicketService implements TicketPortService{
    private $ticketRepository;
    private $eventRepository;
    private $dbConn;
    private $rabbitMq;
    private $param;

    public function __construct($ticketRepository, $eventRepository, $dbConn, $param, $rabbitMq = null)
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
        $event = new Event();
        $event->setId($ticket->getEventId());
        $checkEvent = $this->eventRepository->getEvent($event, $this->dbConn);
        if (count($checkEvent) == 0) {
            return "event not found\n";
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
        
        return "Your ticket has been created.";
    }
    public function checkTicketStatus(Ticket $ticket)
    {
        $resp = [
            'message' => '',
            'status' => 'success',
        ];
        $data = $this->ticketRepository->checkTicketStatus($ticket, $this->dbConn);

        if (!$data) {
            $resp['message'] = 'Ticket not found';
            $resp['status'] = false;
            $resp['data'] = [];
            
            return $resp;
        }

        $resp['data'] = $data;
        return $resp;
    }
    
    public function updateTicket(Ticket $ticket, $status)
    {
        $resp = [
            'message' => '',
            'status' => 'success',
        ];

        $isUpdated = $this->ticketRepository->updateTicket($ticket, $status, $this->dbConn);
        if (!$isUpdated) {
            $resp['message'] = 'Update ticket failed';
            $resp['status'] = false;
            $resp['data'] = [];
            
            return $resp;
        }

        $ticketUpdated = $this->ticketRepository->checkTicketStatus($ticket, $this->dbConn);
        $resp['data'] = $ticketUpdated;

        return $resp;
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