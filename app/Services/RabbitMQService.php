<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use PhpAmqpLib\Connection\AMQPConnectionConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AMQPSSLConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{

    protected $uid;

    public function publish($message)
    {

        $sslOptions = [
            'verify_peer' => false
        ];

        $connection = new AMQPSSLConnection(env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST'),
            $sslOptions,
        );
        $channel = $connection->channel();
        $channel->exchange_declare('stock_queue_exchange', 'fanout', false, false, false);

        $msg = new AMQPMessage($message);
        $channel->basic_publish($msg, 'stock_queue_exchange');
        echo " [x] Sent $message to stock_queue_exchange";
        $channel->close();
        $connection->close();
    }
    public function consume()
    {

        $sslOptions = [
            'verify_peer' => false
        ];

        $connection = new AMQPSSLConnection(env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST'),
            $sslOptions,
        );
        $channel = $connection->channel();
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };
        $channel->queue_declare('queue_name', false, false, false, false);
        $channel->basic_consume('queue_name', '', false, true, false, false, $callback);

        $channel->queue_bind('queue_name', 'stock_queue_exchange');
        Log::info('Waiting for new message on queue_name');
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
}
