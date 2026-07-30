<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeaturedContent extends Model
{
    public const SECTION_MARTYRS = 'featured_martyrs';

    public const SECTION_MEMORY_IMAGES = 'featured_memory_images';

    public const SECTION_STORIES = 'featured_stories';

    public const SECTIONS = [
        self::SECTION_MARTYRS,
        self::SECTION_STORIES,
        self::SECTION_MEMORY_IMAGES,
    ];

    protected $guarded = [];

    public function martyr(): BelongsTo
    {
        return $this->belongsTo(Martyr::class);
    }

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function memoryImage(): BelongsTo
    {
        return $this->belongsTo(MomeriesImg::class, 'momeries_img_id');
    }

    public function scopeForSection(Builder $query, string $section): Builder
    {
        return $query->where('section', $section);
    }

    public static function limitFor(string $section): ?int
    {
        return match ($section) {
            self::SECTION_MARTYRS => 4,
            self::SECTION_STORIES => 3,
            self::SECTION_MEMORY_IMAGES => 4,
            default => null,
        };
    }

    public static function foreignKeyFor(string $section): ?string
    {
        return match ($section) {
            self::SECTION_MARTYRS => 'martyr_id',
            self::SECTION_STORIES => 'story_id',
            self::SECTION_MEMORY_IMAGES => 'momeries_img_id',
            default => null,
        };
    }
}
