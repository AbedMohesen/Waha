<?php

namespace App\Models;

use App\Support\ArabicText;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Martyr extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::saving(function (Martyr $martyr): void {
            $martyr->name_ar_normalized = ArabicText::normalize($martyr->name_ar);
        });
    }

    public function scopeSearchArabicName(Builder $query, string $search): Builder
    {
        $terms = ArabicText::terms($search);

        if ($terms === []) {
            return $query->whereRaw('1 = 0');
        }

        foreach ($terms as $term) {
            $query->whereRaw(
                "name_ar_normalized LIKE ? ESCAPE '!'",
                ['%' . ArabicText::escapeLike($term) . '%']
            );
        }

        return $query;
    }

    public function momeriesImg()
    {
        return $this->hasMany(MomeriesImg::class);
    }

    public function profileImg()
    {
        return $this->hasOne(ProfileImg::class)->withDefault();
    }

    public function story()
    {
        return $this->hasOne(Story::class)->withDefault();
    }

    public function condolence()
    {
        return $this->hasMany(Condolence::class);
    }
}
