<?php

namespace Tests\Feature;

use App\Models\FormSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class FormSubmissionApiTest extends TestCase
{
    use RefreshDatabase;

    private string $apiKey = 'test-internal-key';

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.internal.api_key' => $this->apiKey]);
    }

    private function authHeaders(): array
    {
        return ['X-Internal-Api-Key' => $this->apiKey];
    }

    #[Test]
    public function index_requires_internal_api_key(): void
    {
        $this->getJson('/api/internal/form-submissions')
            ->assertUnauthorized();
    }

    #[Test]
    public function index_rejects_invalid_api_key(): void
    {
        $this->getJson('/api/internal/form-submissions', ['X-Internal-Api-Key' => 'wrong'])
            ->assertUnauthorized();
    }

    #[Test]
    public function index_returns_paginated_submissions(): void
    {
        FormSubmission::create([
            'form_type' => 'contact',
            'url'       => 'https://example.com/contact',
            'payload'   => ['name' => 'Test'],
            'sent_at'   => now(),
        ]);

        $response = $this->getJson('/api/internal/form-submissions', $this->authHeaders());

        $response->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonCount(1, 'data');
    }

    #[Test]
    public function index_filters_by_form_type(): void
    {
        FormSubmission::create(['form_type' => 'contact', 'url' => '/', 'payload' => []]);
        FormSubmission::create(['form_type' => 'newsletter', 'url' => '/', 'payload' => []]);

        $response = $this->getJson('/api/internal/form-submissions?form_type=contact', $this->authHeaders());

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    #[Test]
    public function index_filters_by_search(): void
    {
        FormSubmission::create(['form_type' => 'contact', 'url' => '/', 'payload' => ['name' => 'Jan Kowalski']]);
        FormSubmission::create(['form_type' => 'contact', 'url' => '/', 'payload' => ['name' => 'Anna Nowak']]);

        $response = $this->getJson('/api/internal/form-submissions?search=Kowalski', $this->authHeaders());

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    #[Test]
    public function show_returns_single_submission(): void
    {
        $submission = FormSubmission::create([
            'form_type' => 'contact',
            'url'       => '/',
            'payload'   => ['name' => 'Test'],
        ]);

        $response = $this->getJson("/api/internal/form-submissions/{$submission->id}", $this->authHeaders());

        $response->assertOk()
            ->assertJsonPath('id', $submission->id);
    }

    #[Test]
    public function show_returns_404_for_missing_submission(): void
    {
        $this->getJson('/api/internal/form-submissions/999', $this->authHeaders())
            ->assertNotFound();
    }

    #[Test]
    public function destroy_deletes_submission(): void
    {
        $submission = FormSubmission::create([
            'form_type' => 'contact',
            'url'       => '/',
            'payload'   => ['name' => 'Test'],
        ]);

        $this->deleteJson("/api/internal/form-submissions/{$submission->id}", [], $this->authHeaders())
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('form_submissions', ['id' => $submission->id]);
    }

    #[Test]
    public function destroy_returns_404_for_missing_submission(): void
    {
        $this->deleteJson('/api/internal/form-submissions/999', [], $this->authHeaders())
            ->assertNotFound();
    }
}
