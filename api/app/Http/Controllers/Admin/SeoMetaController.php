<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UpsertSeoMetaRequest;
use App\Models\SeoMeta;
use Illuminate\Http\JsonResponse;

final class SeoMetaController extends AdminController
{
    public function index(): JsonResponse
    {
        $items = SeoMeta::query()->orderBy('page_type')->orderBy('page_slug')->orderBy('id')->get();

        return $this->collection(
            items: $items->map(fn (SeoMeta $item) => $this->format($item))->all(),
            total: $items->count(),
        );
    }

    public function store(UpsertSeoMetaRequest $request): JsonResponse
    {
        $item = SeoMeta::query()->create($request->validated());

        return $this->item($this->format($item), 201);
    }

    public function update(UpsertSeoMetaRequest $request, int $id): JsonResponse
    {
        $item = SeoMeta::query()->find($id);

        if (! $item) {
            return $this->notFound("SEO запись #{$id} не найдена");
        }

        $item->update($request->validated());

        return $this->item($this->format($item));
    }

    public function destroy(int $id): JsonResponse
    {
        $item = SeoMeta::query()->find($id);

        if (! $item) {
            return $this->notFound("SEO запись #{$id} не найдена");
        }

        $item->delete();

        return response()->json(['message' => 'Deleted']);
    }

    private function format(SeoMeta $item): array
    {
        return [
            'id' => $item->id,
            'page_type' => $item->page_type,
            'page_slug' => $item->page_slug,
            'title' => $item->title,
            'description' => $item->description,
            'canonical' => $item->canonical,
            'og_title' => $item->og_title,
            'og_description' => $item->og_description,
            'og_image' => $item->og_image,
        ];
    }
}
