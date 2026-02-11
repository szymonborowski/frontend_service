<?php

namespace App\Services;

use Illuminate\Http\Request;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AnalyticsEventPublisher
{
    public function publishPostViewed(string $postUuid, ?int $userId, Request $request): void
    {
        try {
            $connection = new AMQPStreamConnection(
                config('rabbitmq.host'),
                config('rabbitmq.port'),
                config('rabbitmq.user'),
                config('rabbitmq.password'),
                config('rabbitmq.vhost'),
            );

            $channel = $connection->channel();

            $exchange = config('rabbitmq.exchanges.analytics');
            $channel->exchange_declare($exchange, 'topic', false, true, false);

            $payload = json_encode([
                'post_uuid' => $postUuid,
                'user_id' => $userId,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->header('referer'),
                'viewed_at' => now()->toIso8601String(),
            ]);

            $message = new AMQPMessage($payload, [
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
                'content_type' => 'application/json',
            ]);

            $channel->basic_publish($message, $exchange, 'post.viewed');

            $channel->close();
            $connection->close();
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
