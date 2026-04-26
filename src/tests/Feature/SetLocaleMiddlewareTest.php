<?php

namespace Tests\Feature;

use Tests\TestCase;

class SetLocaleMiddlewareTest extends TestCase
{
    public function test_query_param_overrides_session_and_persists_to_session(): void
    {
        $this->withSession(['locale' => 'en'])
            ->get('/?lang=pl')
            ->assertOk();

        $this->assertSame('pl', app()->getLocale());
        $this->assertSame('pl', session('locale'));
    }

    public function test_session_locale_is_used_when_no_query_param(): void
    {
        $this->withSession(['locale' => 'pl'])
            ->get('/')
            ->assertOk();

        $this->assertSame('pl', app()->getLocale());
    }

    public function test_accept_language_header_used_when_no_session(): void
    {
        $this->get('/', ['Accept-Language' => 'pl-PL,pl;q=0.9,en;q=0.8'])
            ->assertOk();

        $this->assertSame('pl', app()->getLocale());
    }

    public function test_falls_back_to_config_when_accept_language_unsupported(): void
    {
        config(['app.locale' => 'en']);

        $this->get('/', ['Accept-Language' => 'de-DE,fr;q=0.9'])
            ->assertOk();

        $this->assertSame('en', app()->getLocale());
    }

    public function test_invalid_query_param_is_ignored(): void
    {
        $this->withSession(['locale' => 'en'])
            ->get('/?lang=de')
            ->assertOk();

        $this->assertSame('en', app()->getLocale());
    }

    public function test_query_param_does_not_persist_when_invalid(): void
    {
        $this->get('/?lang=de')->assertOk();

        $this->assertNull(session('locale'));
    }
}
