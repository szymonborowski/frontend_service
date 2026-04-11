<?php

namespace App\Console\Commands;

use App\Services\BlogEventsMessageHandler;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumeBlogEvents extends Command
{
    protected $signature = 'rabbitmq:consume-blog';

    protected $description = 'Consume blog events from RabbitMQ and update Qdrant index';

    private ?AMQPStreamConnection $connection = null;

    public function __construct(
        private readonly BlogEventsMessageHandler $messageHandler,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Starting blog events consumer...');

        $connection = $this->getConnection();
        $channel    = $connection->channel();

        $exchange = config('rabbitmq.exchanges.blog');
        $queue    = config('rabbitmq.queues.frontend_blog');

        $channel->exchange_declare($exchange, 'topic', false, true, false);
        $channel->queue_declare($queue, false, true, false, false);
        $channel->queue_bind($queue, $exchange, 'post.*');

        $this->info("Waiting for messages on queue: {$queue}");

        $channel->basic_consume(
            $queue,
            '',
            false,
            false,
            false,
            false,
            function ($message) {
                $this->processMessage($message);
            }
        );

        while ($channel->is_consuming()) {
            $channel->wait();
        }

        return Command::SUCCESS;
    }

    private function processMessage($message): void
    {
        try {
            $data   = json_decode($message->body, true);
            $action = $data['action'] ?? 'unknown';
            $slug   = $data['post']['slug'] ?? 'unknown';

            $this->info("Processing post.{$action} for slug: {$slug}");
            $this->messageHandler->handle($message->body);
            $message->ack();
            $this->info("Indexed: {$slug}");
        } catch (\Exception $e) {
            $data = json_decode($message->body, true);
            $slug = $data['post']['slug'] ?? 'unknown';

            $this->error("Failed to index [{$slug}]: {$e->getMessage()}");
            logger()->error('ConsumeBlogEvents: indexing failed', [
                'slug'      => $slug,
                'error'     => $e->getMessage(),
                'exception' => $e,
            ]);

            sleep(5);
            $message->nack(false, true); // requeue: true
        }
    }

    private function getConnection(): AMQPStreamConnection
    {
        if ($this->connection === null || !$this->connection->isConnected()) {
            $this->connection = new AMQPStreamConnection(
                config('rabbitmq.host'),
                config('rabbitmq.port'),
                config('rabbitmq.user'),
                config('rabbitmq.password'),
                config('rabbitmq.vhost'),
            );
        }

        return $this->connection;
    }

    public function __destruct()
    {
        if ($this->connection !== null && $this->connection->isConnected()) {
            $this->connection->close();
        }
    }
}
