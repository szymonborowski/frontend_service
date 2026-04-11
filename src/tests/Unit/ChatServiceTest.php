<?php

namespace Tests\Unit;

use App\Services\AnthropicClient;
use App\Services\BlogApiService;
use App\Services\ChatService;
use App\Services\ContactNotificationService;
use App\Services\QdrantClient;
use App\Services\VoyageClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ChatServiceTest extends TestCase
{
    private string $sessionId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sessionId = 'test-' . uniqid();
        config(['services.blog.url' => 'http://blog-test']);
    }

    protected function tearDown(): void
    {
        Redis::connection()->del("chat:{$this->sessionId}");
        Redis::connection()->del("chat:{$this->sessionId}:state");
        parent::tearDown();
    }

    private function makeService(
        ?AnthropicClient $anthropic = null,
        ?BlogApiService $blog = null,
        ?VoyageClient $voyage = null,
        ?QdrantClient $qdrant = null,
        ?ContactNotificationService $contact = null,
    ): ChatService {
        $voyage ??= $this->voyageMock();
        $qdrant ??= $this->qdrantMock();
        $blog   ??= $this->blogMock();

        return new ChatService(
            $anthropic ?? $this->createMock(AnthropicClient::class),
            $blog,
            $voyage,
            $qdrant,
            $contact ?? $this->createMock(ContactNotificationService::class),
        );
    }

    private function voyageMock(): VoyageClient
    {
        $mock = $this->createMock(VoyageClient::class);
        $mock->method('embed')->willReturn(array_fill(0, 1024, 0.1));
        return $mock;
    }

    private function qdrantMock(array $results = []): QdrantClient
    {
        $mock = $this->createMock(QdrantClient::class);
        $mock->method('search')->willReturn($results);
        return $mock;
    }

    private function blogMock(array $posts = []): BlogApiService
    {
        Http::fake(['blog-test/api/v1/posts*' => Http::response(['data' => $posts], 200)]);
        return app(BlogApiService::class);
    }

    // -------------------------------------------------------------------------

    #[Test]
    public function sendMessage_returns_reply_from_anthropic(): void
    {
        $anthropic = $this->createMock(AnthropicClient::class);
        $anthropic->method('sendMessage')->willReturn('Hello from Aina!');

        $reply = $this->makeService(anthropic: $anthropic)
            ->sendMessage('Hi', $this->sessionId);

        $this->assertSame('Hello from Aina!', $reply);
    }

    #[Test]
    public function sendMessage_passes_user_message_in_history_to_anthropic(): void
    {
        $anthropic = $this->createMock(AnthropicClient::class);
        $anthropic->expects($this->once())
            ->method('sendMessage')
            ->with(
                $this->isString(),
                $this->callback(fn($messages) =>
                    $messages[0]['role'] === 'user' &&
                    $messages[0]['content'] === 'What is Laravel?'
                )
            )
            ->willReturn('Laravel is a PHP framework.');

        $this->makeService(anthropic: $anthropic)
            ->sendMessage('What is Laravel?', $this->sessionId);
    }

    #[Test]
    public function sendMessage_stores_and_replays_history_across_calls(): void
    {
        $anthropic = $this->createMock(AnthropicClient::class);
        $anthropic->method('sendMessage')
            ->willReturnOnConsecutiveCalls('First reply', 'Second reply');

        $service = $this->makeService(anthropic: $anthropic);
        $service->sendMessage('First question', $this->sessionId);

        // Second call should pass both messages from first exchange in history
        $anthropic->expects($this->once())
            ->method('sendMessage')
            ->with(
                $this->isString(),
                $this->callback(fn($messages) => count($messages) === 3) // first Q + first A + second Q
            )
            ->willReturn('Second reply');

        // Re-create service to re-read history from Redis
        $service->sendMessage('Second question', $this->sessionId);
    }

    #[Test]
    public function sendMessage_trims_history_when_exceeding_max_pairs(): void
    {
        $anthropic = $this->createMock(AnthropicClient::class);
        $anthropic->method('sendMessage')->willReturn('ok');

        $service = $this->makeService(anthropic: $anthropic);

        // Send MAX_HISTORY_PAIRS (10) + 2 extra exchanges
        for ($i = 0; $i < 12; $i++) {
            $service->sendMessage("Message {$i}", $this->sessionId);
        }

        // History in Redis should not exceed MAX_HISTORY_PAIRS * 2 = 20 messages
        $raw     = Redis::connection()->get("chat:{$this->sessionId}");
        $history = json_decode($raw, true);

        $this->assertLessThanOrEqual(20, count($history));
    }

    #[Test]
    public function sendMessage_removes_contact_ready_token_from_reply(): void
    {
        $anthropic = $this->createMock(AnthropicClient::class);
        $anthropic->method('sendMessage')
            ->willReturn('Your message has been sent! [CONTACT_READY]');

        $contact = $this->createMock(ContactNotificationService::class);
        $contact->method('extractFromHistory')->willReturn(['email' => '', 'phone' => null, 'name' => 'Test', 'message' => '']);
        $contact->method('send');

        $reply = $this->makeService(anthropic: $anthropic, contact: $contact)
            ->sendMessage('Send it', $this->sessionId);

        $this->assertStringNotContainsString('[CONTACT_READY]', $reply);
        $this->assertStringContainsString('Your message has been sent!', $reply);
    }

    #[Test]
    public function sendMessage_sends_notification_when_contact_ready_and_email_present(): void
    {
        $anthropic = $this->createMock(AnthropicClient::class);
        $anthropic->method('sendMessage')
            ->willReturn('Message sent! [CONTACT_READY]');

        $contact = $this->createMock(ContactNotificationService::class);
        $contact->method('extractFromHistory')->willReturn([
            'email'   => 'jan@example.com',
            'phone'   => '+48 123 456 789',
            'name'    => 'Jan',
            'message' => 'Hello Szymon!',
        ]);
        $contact->expects($this->once())->method('send');

        $this->makeService(anthropic: $anthropic, contact: $contact)
            ->sendMessage('confirm', $this->sessionId);
    }

    #[Test]
    public function sendMessage_does_not_send_notification_when_email_missing(): void
    {
        $anthropic = $this->createMock(AnthropicClient::class);
        $anthropic->method('sendMessage')->willReturn('Done! [CONTACT_READY]');

        $contact = $this->createMock(ContactNotificationService::class);
        $contact->method('extractFromHistory')->willReturn([
            'email' => '', 'phone' => null, 'name' => 'Unknown', 'message' => '',
        ]);
        $contact->expects($this->never())->method('send');

        $this->makeService(anthropic: $anthropic, contact: $contact)
            ->sendMessage('confirm', $this->sessionId);
    }

    #[Test]
    public function clearHistory_removes_redis_keys(): void
    {
        $anthropic = $this->createMock(AnthropicClient::class);
        $anthropic->method('sendMessage')->willReturn('reply');

        $service = $this->makeService(anthropic: $anthropic);
        $service->sendMessage('Hi', $this->sessionId);

        $this->assertNotNull(Redis::connection()->get("chat:{$this->sessionId}"));

        $service->clearHistory($this->sessionId);

        $this->assertNull(Redis::connection()->get("chat:{$this->sessionId}"));
        $this->assertNull(Redis::connection()->get("chat:{$this->sessionId}:state"));
    }
}
