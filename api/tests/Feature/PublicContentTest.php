<?php

use App\Models\Review;
use App\Models\SeoMeta;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns only active reviews sorted by newest first', function () {
    Review::query()->create([
        'author_name' => 'Visible old',
        'text' => 'Old review',
        'rating' => 4,
        'date' => '2026-04-01',
        'is_active' => true,
    ]);

    Review::query()->create([
        'author_name' => 'Hidden',
        'text' => 'Hidden review',
        'rating' => 5,
        'date' => '2026-04-03',
        'is_active' => false,
    ]);

    Review::query()->create([
        'author_name' => 'Visible new',
        'text' => 'New review',
        'rating' => 5,
        'date' => '2026-04-04',
        'is_active' => true,
    ]);

    $response = $this->getJson('/api/reviews')->assertOk();

    expect($response->json('data'))->toHaveCount(2);
    expect($response->json('data.0.author_name'))->toBe('Visible new');
    expect($response->json('data.1.author_name'))->toBe('Visible old');
});

it('returns public seo overrides by page type and slug', function () {
    SeoMeta::query()->create([
        'page_type' => 'warehouse',
        'page_slug' => 'test-warehouse',
        'title' => 'SEO title',
        'description' => 'SEO description',
        'canonical' => 'https://example.com/test-warehouse',
    ]);

    $this->getJson('/api/seo/warehouse/test-warehouse')
        ->assertOk()
        ->assertJsonPath('data.title', 'SEO title')
        ->assertJsonPath('data.description', 'SEO description')
        ->assertJsonPath('data.canonical', 'https://example.com/test-warehouse');
});
