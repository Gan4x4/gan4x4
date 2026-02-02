<?php

namespace App;

class Experience extends EnhancedModel

{
    protected $fillable = [
        'name_en',
        'name_ru',
        'description_en',
        'description_ru',
        'position_en',
        'position_ru',
        'duties_en',
        'duties_ru',
        'start',
        'end',
        'url',
        'logo',
    ];
   
   
    
    public function getDutiesAttribute($d){
        return self::text2web($d);
    }


}
