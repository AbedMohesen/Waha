<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Martyr extends Model
{
    protected $guarded = [];
    public function momeriesImg()
    {
        return $this->hasOne(MomeriesImg::class)->withDefault();
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
        return $this->hasOne(Condolence::class)->withDefault();
    }
}
