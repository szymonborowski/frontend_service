<?php

namespace Tests\Feature;

use App\Mail\ContactNotification;
use App\Models\FormSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'name'    => 'Jan Kowalski',
            'email'   => 'jan@example.com',
            'phone'   => '+48 123 456 789',
            'subject' => 'Test subject',
            'message' => 'This is a test message with enough characters.',
        ], $overrides);
    }

    #[Test]
    public function successful_submission_returns_json_success(): void
    {
        Mail::fake();

        $response = $this->postJson(route('contact.send'), $this->validPayload());

        $response->assertOk()
            ->assertJson(['success' => true]);

        Mail::assertSent(ContactNotification::class);
        $this->assertDatabaseHas('form_submissions', ['form_type' => 'contact']);
    }

    #[Test]
    public function submission_stores_form_data_and_marks_sent(): void
    {
        Mail::fake();

        $this->postJson(route('contact.send'), $this->validPayload());

        $submission = FormSubmission::first();
        $this->assertNotNull($submission);
        $this->assertEquals('contact', $submission->form_type);
        $this->assertEquals('Jan Kowalski', $submission->payload['name']);
        $this->assertNotNull($submission->sent_at);
    }

    #[Test]
    public function phone_is_optional(): void
    {
        Mail::fake();

        $response = $this->postJson(route('contact.send'), $this->validPayload(['phone' => null]));

        $response->assertOk()
            ->assertJson(['success' => true]);
    }

    #[Test]
    public function validation_fails_when_name_missing(): void
    {
        $response = $this->postJson(route('contact.send'), $this->validPayload(['name' => '']));

        $response->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonValidationErrors('name');
    }

    #[Test]
    public function validation_fails_when_email_invalid(): void
    {
        $response = $this->postJson(route('contact.send'), $this->validPayload(['email' => 'not-an-email']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    #[Test]
    public function validation_fails_when_subject_missing(): void
    {
        $response = $this->postJson(route('contact.send'), $this->validPayload(['subject' => '']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors('subject');
    }

    #[Test]
    public function validation_fails_when_message_too_short(): void
    {
        $response = $this->postJson(route('contact.send'), $this->validPayload(['message' => 'short']));

        $response->assertStatus(422)
            ->assertJsonValidationErrors('message');
    }

    #[Test]
    public function validation_fails_when_message_too_long(): void
    {
        $response = $this->postJson(route('contact.send'), $this->validPayload(['message' => str_repeat('a', 5001)]));

        $response->assertStatus(422)
            ->assertJsonValidationErrors('message');
    }
}
