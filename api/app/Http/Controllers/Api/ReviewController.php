<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use Illuminate\Http\JsonResponse;

final class ReviewController extends ApiController
{
    public function index(): JsonResponse
    {
        $items = Review::query()
            ->where('is_active', true)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return $this->collection(
            items: $items->map(fn (Review $item) => [
                'id' => $item->id,
                'author_name' => $item->author_name,
                'text' => $item->text,
                'rating' => $item->rating,
                'date' => $item->date?->toDateString(),
                'photo_url' => $item->photo_url,
                'source_url' => $item->source_url,
                'is_active' => (bool) $item->is_active,
            ])->all(),
            total: $items->count(),
            page: 1,
            perPage: $items->count(),
        );
    }
}
