<?php

namespace App\Livewire;

use App\Services\BlogApiService;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SearchBox extends Component
{
    public string $query = '';
    public array $results = [];
    public bool $isOpen = false;
    public bool $loading = false;

    protected BlogApiService $blogApiService;

    private const ABOUT_KEYWORDS = ['szymon', 'borowski', 'autor', 'author', 'o mnie', 'about me', 'about'];

    public function boot(BlogApiService $blogApiService): void
    {
        $this->blogApiService = $blogApiService;
    }

    public function open(): void
    {
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
        $this->query  = '';
        $this->results = [];
    }

    public function updatedQuery(): void
    {
        if (mb_strlen($this->query) < 2) {
            $this->results = [];
            return;
        }

        $this->loading = true;
        $locale        = app()->getLocale();
        $this->results = $this->blogApiService->search($this->query, $locale);
        $this->loading = false;
    }

    #[Computed]
    public function showAboutResult(): bool
    {
        if (mb_strlen($this->query) < 2) {
            return false;
        }

        $lower = mb_strtolower($this->query);
        foreach (self::ABOUT_KEYWORDS as $keyword) {
            if (str_contains($lower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    #[Computed]
    public function isEmpty(): bool
    {
        return mb_strlen($this->query) >= 2
            && !$this->loading
            && empty($this->results['posts'])
            && empty($this->results['categories'])
            && empty($this->results['tags'])
            && !$this->showAboutResult;
    }

    public function render()
    {
        return view('livewire.search-box');
    }
}
