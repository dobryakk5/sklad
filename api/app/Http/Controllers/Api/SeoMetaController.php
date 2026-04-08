<?php

namespace App\Http\Controllers\Api;

use App\Models\SeoMeta;
use Illuminate\Http\JsonResponse;

final class SeoMetaController extends ApiController
{
    public function show(string $pageType, string $pageSlug): JsonResponse
    {
        $item = SeoMeta::query()
            ->where('page_type', $pageType)
            ->where('page_slug', $pageSlug)
            ->first();

        if (! $item) {
            return $this->notFound('SEO запись не найдена');
        }

        return $this->item([
            'title' => $item->title,
            'description' => $item->description,
            'canonical' => $item->canonical,
            'og_title' => $item->og_title,
            'og_description' => $item->og_description,
            'og_image' => $item->og_image,
        ]);
    }
}
