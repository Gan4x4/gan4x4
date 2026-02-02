<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Lang;

class Video extends EnhancedModel
{
    protected $fillable = [
        'name_en',
        'name_ru',
        'description_en',
        'description_ru',
        'url',
        'image',
        'code',
    ];
}
