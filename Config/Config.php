<?php

namespace App\Config;

require __DIR__ . './../vendor/autoload.php';

use PDO;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Config
{
    public function getParameter(string $key = "")
    {
        $parameterLists = require __DIR__ . '/../params.php';
        return ($key != "") ? $parameterLists[$key] : $parameterLists;
    }

    public function dbConnection()
    {
        $param = $this->getParameter('db');
        
        return new PDO(
            $param['db_driver'].':host='.$param['host'].';dbname='.$param['db_name'],
            $param['username'],
            $param['password']);
    }

    public function rabbitMQConnection(string $queueName)
    {
        $param = $this->getParameter('rabbitmq');
        $connection = new AMQPStreamConnection($param['host'], $param['port'], $param['username'], $param['password']);
        $channel = $connection->channel();
        $channel->queue_declare($queueName, false, false, false, false);
        
        return [
            'channel' => $channel,
            'connection' => $connection,
            'queueName' => $queueName,
        ];
    }
}