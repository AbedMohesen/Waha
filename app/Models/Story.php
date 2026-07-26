<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $guarded = [];

    public function martyr()
    {
        return $this->belongsTo(Martyr::class)->withDefault();
    }
}
