<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class SeoMeta extends Model
{
    protected $table = 'seo_meta';

    protected $fillable = [
        'page_type',
        'page_slug',
        'title',
        'description',
        'canonical',
        'og_title',
        'og_description',
        'og_image',
    ];
}
