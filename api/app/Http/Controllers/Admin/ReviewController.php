<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpsertReviewRequest;
use App\Models\Review;
use Illuminate\Http\JsonResponse;

final class ReviewController extends AdminController
{
    public function index(): JsonResponse
    {
        $items = Review::query()->orderByDesc('date')->orderByDesc('id')->get();

        return $this->collection(
            items: $items->map(fn (Review $item) => $this->format($item))->all(),
            total: $items->count(),
        );
    }

    public function store(UpsertReviewRequest $request): JsonResponse
    {
        $item = Review::query()->create($request->validated());

        return $this->item($this->format($item), 201);
    }

    public function update(UpsertReviewRequest $request, int $id): JsonResponse
    {
        $item = Review::query()->find($id);

        if (! $item) {
            return $this->notFound("Отзыв #{$id} не найден");
        }

        $item->update($request->validated());

        return $this->item($this->format($item));
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Review::query()->find($id);

        if (! $item) {
            return $this->notFound("Отзыв #{$id} не найден");
        }

        $item->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function format(Review $item): array
    {
        return [
            'id' => $item->id,
            'author_name' => $item->author_name,
            'text' => $item->text,
            'rating' => $item->rating,
            'date' => $item->date?->toDateString(),
            'photo_url' => $item->photo_url,
            'source_url' => $item->source_url,
            'is_active' => (bool) $item->is_active,
        ];
    }
}
