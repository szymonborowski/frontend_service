<?php

namespace Tests\Feature;

use App\Services\ChatService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock ChatService for all HTTP-layer tests
        $this->mock(ChatService::class, function ($mock) {
            $mock->shouldReceive('sendMessage')->andReturn('Mocked reply from Aina.')->byDefault();
            $mock->shouldReceive('clearHistory')->andReturn(null)->byDefault();
        });
    }

    // -------------------------------------------------------------------------

    #[Test]
    public function send_returns_reply_on_valid_message(): void
    {
        $response = $this->postJson(route('chat.send'), ['message' => 'Hello Aina!']);

        $response->assertOk()
            ->assertJsonStructure(['reply'])
            ->assertJson(['reply' => 'Mocked reply from Aina.']);
    }

    #[Test]
    public function send_returns_422_when_message_is_missing(): void
    {
        $response = $this->postJson(route('chat.send'), []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('message');
    }

    #[Test]
    public function send_returns_422_when_message_is_too_short(): void
    {
        $response = $this->postJson(route('chat.send'), ['message' => 'A']);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('message');
    }

    #[Test]
    public function send_returns_422_when_message_exceeds_500_chars(): void
    {
        $response = $this->postJson(route('chat.send'), ['message' => str_repeat('x', 501)]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('message');
    }

    #[Test]
    public function send_strips_html_tags_from_user_message_before_passing_to_service(): void
    {
        $captured = null;

        $this->mock(ChatService::class, function ($mock) use (&$captured) {
            $mock->shouldReceive('sendMessage')
                ->once()
                ->withArgs(function ($message) use (&$captured) {
                    $captured = $message;
                    return true;
                })
                ->andReturn('safe reply');
        });

        $this->postJson(route('chat.send'), [
            'message' => '<script>alert("xss")</script>Tell me about Szymon',
        ]);

        $this->assertStringNotContainsString('<script>', $captured);
        $this->assertStringContainsString('Tell me about Szymon', $captured);
    }

    #[Test]
    public function send_returns_503_when_service_throws_runtime_exception(): void
    {
        $this->mock(ChatService::class, function ($mock) {
            $mock->shouldReceive('sendMessage')
                ->andThrow(new \RuntimeException('Anthropic API error'));
        });

        $response = $this->postJson(route('chat.send'), ['message' => 'Hello!']);

        $response->assertStatus(503)
            ->assertJson(['error' => 'Service unavailable. Please try again later.']);
    }

    #[Test]
    public function clear_returns_ok(): void
    {
        $response = $this->postJson(route('chat.clear'));

        $response->assertOk()->assertJson(['ok' => true]);
    }
}
