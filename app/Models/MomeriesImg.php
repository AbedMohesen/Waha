<?php

namespace App\Models;

use App\Models\Martyr;
use Illuminate\Database\Eloquent\Model;

class MomeriesImg extends Model
{
    protected $guarded = [];

    function martyr()
    {
        return $this->belongsTo(Martyr::class)->withDefault();
    }
}
